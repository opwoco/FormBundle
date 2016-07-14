<?php
namespace Alsatian\FormBundle\Form\Extensions;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Alsatian\FormBundle\Form\ExtensibleChoiceType;
use Alsatian\FormBundle\Form\ExtensibleDocumentType;
use Alsatian\FormBundle\Form\ExtensibleEntityType;

use Symfony\Component\Form\ResolvedFormTypeInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ExtensibleSubscriber implements EventSubscriberInterface
{
    private $enabledTypes;

    private $em = null;
    private $dm = null;
    private $accessor = null;
    
    public function __construct($enabledTypes)
    {
        $this->enabledTypes = $enabledTypes;
    }
 
    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    public function setDocumentManager($dm)
    {
        $this->dm = $dm;
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => array('populateAjaxChoices',-50),
            FormEvents::PRE_SUBMIT   => array('populateAjaxChoices',-50)
        );
    }
         
    private function getListenedType(ResolvedFormTypeInterface $type)
    {
        while($type){
            if(in_array(get_class($type->getInnerType()),$this->enabledTypes)){
                return get_class($type->getInnerType());
            }
            
            $type = $type->getParent();
        }
        
        return false;
    }
     
    public function populateAjaxChoices(FormEvent $event)
    {
        foreach($event->getForm()->all() as $child){
            if($type = $this->getListenedType($child->getConfig()->getType())){
                $this->populateAjaxChoice($event,$child->getName(),$type);
            }
        }
    }
     
    private function populateAjaxChoice(FormEvent $event,$childName, $type)
    {   
        $form = $event->getForm();
        $child = $form->get($childName);
        $options = $child->getConfig()->getOptions();

        $choices = array();

        $data = $event->getData();
        if(is_array($data)){$property = '['.$childName.']';}
        else
        {$property = $childName;}
        
        if(!$this->isReadable($data,$property)){return;}
        
        $data = $this->getValue($data,$property);
        if(!$data){return;}
        
        switch($type)
        {
            case ExtensibleEntityType::class :
            case ExtensibleDocumentType::class :
                if(is_array($data) || $data instanceOf \Traversable){
                    foreach($data as $Entity){
                        $this->addChoice($choices,$Entity,$options['class'],$type);
                    }
                }
                else{
                    $this->addChoice($choices,$data,$options['class'],$type);
                }
            break;
            case ExtensibleChoiceType::class:
                if(is_array($data)){
                    foreach($data as $choice){
                        $choices[$choice] = $choice;
                    }
                }
                else{
                    $choices[$data] = $data;
                }
            break;
        } 

        // Since line 72 is returning all resolved option, only these options are reused :
        $newOptions = array('constraints'=>$options['constraints'],'choice_label'=>$options['choice_label'],'route'=>$options['route'],'route_params'=>$options['route_params'],'required'=>$options['required'],'multiple'=>$options['multiple'],'choices'=>$choices);
        
        if(array_key_exists('class',$options)){$newOptions=array_merge($newOptions,array('class'=>$options['class']));}
        $form->add($childName,$type,$newOptions);
    }

    private function addChoice(&$array,$data,$class,$type){
        if(is_object($data)){
             $array[] = $data;
        }
        else{
            switch($type){
                case ExtensibleEntityType::class :
                    $array[] = $this->em->getRepository($class)->findOneById($data);
                break;
                case ExtensibleDocumentType::class :
                    $array[] = $this->dm->getRepository($class)->findOneById($data);
                break;
            }
        }
    }
    
    private function getPropertyAccessor()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }
    
    private function isReadable($data,$property){
        if(!$this->accessor){
            $this->getPropertyAccessor();
        }
        
        return $this->accessor->isReadable($data,$property);
    }
    
    private function getValue($data,$property){
        if(!$this->accessor){
            $this->getPropertyAccessor();
        }
        
        return $this->accessor->getValue($data,$property);
    }
}
