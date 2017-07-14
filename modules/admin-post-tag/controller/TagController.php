<?php
/**
 * Tag controller
 * @package admin-post-tag
 * @version 0.0.1
 * @upgrade true
 */

namespace AdminPostTag\Controller;
use PostTag\Model\PostTag as PTag;
use PostTag\Model\PostTagChain as PTChain;

class TagController extends \AdminController
{
    private function _defaultParams(){
        return [
            'title'             => 'Post Tag',
            'nav_title'         => 'Post',
            'active_menu'       => 'post',
            'active_submenu'    => 'post-tag',
            'total'             => 0,
            'pagination'        => []
        ];
    }
    
    
    
    public function editAction(){
        if(!$this->user->login)
            return $this->show404();
        
        $id = $this->param->id;
        if(!$id && !$this->can_i->create_post_tag)
            return $this->show404();
        elseif($id && !$this->can_i->update_post_tag)
            return $this->show404();
        
        $old = null;
        $params = $this->_defaultParams();
        $params['title'] = 'Create New Post Tag';
        $params['ref'] = $this->req->getQuery('ref') ?? $this->router->to('adminPostTag');
        
        if($id){
            $params['title'] = 'Edit Post Tag';
            $object = PTag::get($id, false);
            if(!$object)
                return $this->show404();
            $old = $object;
        }else{
            $object = new \stdClass();
            $object->user = $this->user->id;
        }
        
        if(false === ($form=$this->form->validate('admin-post-tag', $object)))
            return $this->respond('post/tag/edit', $params);
        
        $object = object_replace($object, $form);
        
        $event = 'updated';
        
        if(!$id){
            $event = 'created';
            if(false === ($id = PTag::create($object)))
                throw new \Exception(PTag::lastError());
        }else{
            $object->updated = null;
            if(false === PTag::set($object, $id))
                throw new \Exception(PTag::lastError());
        }
        
        $this->fire('post-tag:'. $event, $object, $old);
        
        return $this->redirect($params['ref']);
    }
    
    public function indexAction(){
        if(!$this->user->login)
            return $this->loginFirst('adminLogin');
        if(!$this->can_i->read_post_tag)
            return $this->show404();
        
        $params = $this->_defaultParams();
        $params['reff']  = $this->req->url;
        $params['tags'] = [];
        
        $page = $this->req->getQuery('page', 1);
        $rpp  = 20;
        $cond = [];
        if($this->req->getQuery('q'))
            $cond['q'] = $this->req->getQuery('q');
        
        $tags = PTag::get($cond, $rpp, $page, 'name ASC');
        if($tags)
            $params['tags'] = \Formatter::formatMany('post-tag', $tags, false, false);
        
        $params['total'] = $total = PTag::count($cond);
        
        if($total > $rpp)
            $params['pagination'] = \calculate_pagination($page, $rpp, $total, 10, $cond);
        
        $this->form->setForm('admin-post-tag-index');
        
        return $this->respond('post/tag/index', $params);
    }
    
    public function removeAction(){
        if(!$this->user->login)
            return $this->show404();
        if(!$this->can_i->remove_post_tag)
            return $this->show404();
        
        $id = $this->param->id;
        $object = PTag::get($id, false);
        if(!$object)
            return $this->show404();
        
        $this->fire('post-tag:deleted', $object);
        PTag::remove($id);
        PTChain::remove(['post_tag'=>$id]);
        
        $ref = $this->req->getQuery('ref');
        if($ref)
            return $this->redirect($ref);
        
        return $this->redirectUrl('adminPostTag');
    }
}