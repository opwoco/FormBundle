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
        $return = array(
            'original'  => get_class($type->getInnerType())
        );
        while($type){
            if(in_array(get_class($type->getInnerType()),$this->enabledTypes)){
                $return['type'] = get_class($type->getInnerType());
                return $return;
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
        $original = $type['original'];
        $type = $type['type'];
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

        $options['choices'] = $choices;
        
        // This line is to avoid 'You cannot set both an "em" and "document_manager" option.' error with DocumentType
        // See DoctrineMongoDBBundle issue #377
        if(array_key_exists('em',$options)){unset($options['em']);}

        $form->add($childName,$original,$options);
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
