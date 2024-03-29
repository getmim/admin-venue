<?php
/**
 * VenueController
 * @package admin-venue
 * @version 0.0.1
 */

namespace AdminVenue\Controller;

use LibFormatter\Library\Formatter;
use LibForm\Library\Form;
use LibForm\Library\Combiner;
use LibPagination\Library\Paginator;
use Venue\Model\Venue;

class VenueController extends \Admin\Controller
{
    private function getParams(string $title): array{
        return [
            '_meta' => [
                'title' => $title,
                'menus' => ['venue', 'all-venue']
            ],
            'subtitle' => $title,
            'pages' => null
        ];
    }

    public function editAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_venue)
            return $this->show404();

        $venue = (object)[
            'status' => 1
        ];

        $id = $this->req->param->id;
        if($id){
            $venue = Venue::getOne(['id'=>$id]);
            if(!$venue)
                return $this->show404();
            $params = $this->getParams('Edit Venue');
        }else{
            $params = $this->getParams('Create New Venue');
        }

        $form              = new Form('admin.venue.edit');
        $params['form']    = $form;

        $c_opts = [
            'contact'    => [null,              null, 'json'],
            'cover'      => [null,              null, 'json'],
            'meta'       => [null,              null, 'json'],
            'open_days'  => [null,              null, 'array'],
            'open_hours' => [null,              null, 'json'],
            'prices'     => [null,              null, 'json'],
            'socials'    => [null,              null, 'json'],

            'category'   => ['venue-category',  null, 'format', 'all', 'name'],
            'facility'   => ['venue-facility',  null, 'format', 'all', 'name'],
            'food'       => ['venue-food',      null, 'format', 'all', 'name'],

            'gallery_food'       => ['admin-venue-gallery', null, 'format', 'active', 'title'],
            'gallery_atmosphere' => ['admin-venue-gallery', null, 'format', 'active', 'title'],
            'gallery_employee'   => ['admin-venue-gallery', null, 'format', 'active', 'title'],
            'gallery_property'   => ['admin-venue-gallery', null, 'format', 'active', 'title']
        ];

        $combiner = new Combiner($id, $c_opts, 'venue');
        $venue    = $combiner->prepare($venue);

        $params['opts'] = $combiner->getOptions();
        
        if(!($valid = $form->validate($venue)) || !$form->csrfTest('noob'))
            return $this->resp('venue/edit', $params);

        $valid = $combiner->finalize($valid);

        if (isset($valid->status)) {
            if (!$this->can_i->publish_venue) {
                unset($valid->status);
            }else{
                if ($valid->status == 2 && $valid->status != $venue->status){
                    $valid->publisher = $this->user->id;
                    $valid->published = date('Y-m-d H:i:s');
                }
            }
        }

        if($id){
            if(!Venue::set((array)$valid, ['id'=>$id]))
                deb(Venue::lastError());
        }else{
            $valid->user = $this->user->id;
            if(!($id = Venue::create((array)$valid)))
                deb(Venue::lastError());
        }

        $combiner->save($id, $this->user->id);

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => $id ? 2 : 1,
            'type'   => 'venue',
            'original' => $venue,
            'changes'  => $valid
        ]);

        $next = $this->router->to('adminVenue');
        $this->res->redirect($next);
    }

    public function indexAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_venue)
            return $this->show404();

        $cond = $pcond = [];
        if($q = $this->req->getQuery('q'))
            $pcond['q'] = $cond['q'] = $q;

        if($status = $this->req->getQuery('status'))
            $pcond['status'] = $cond['status'] = $status;
        else
            $cond['status'] = ['__op', '>', 0];

        list($page, $rpp) = $this->req->getPager(25, 50);

        $venues = Venue::get($cond, $rpp, $page, ['title'=>true]) ?? [];
        if($venues)
            $venues = Formatter::formatMany('venue', $venues, ['user']);

        $params           = $this->getParams('Venue');
        $params['venues'] = $venues;
        $params['form']   = new Form('admin.venue.index');

        $params['form']->validate( (object)$this->req->get() );

        // pagination
        $params['total'] = $total = Venue::count($cond);
        if($total > $rpp){
            $params['pages'] = new Paginator(
                $this->router->to('adminVenue'),
                $total,
                $page,
                $rpp,
                10,
                $pcond
            );
        }

        $this->resp('venue/index', $params);
    }

    public function removeAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->remove_venue)
            return $this->show404();

        $id     = $this->req->param->id;
        $venue  = Venue::getOne(['id'=>$id]);
        $next   = $this->router->to('adminVenue');
        $form   = new Form('admin.venue.index');

        if(!$form->csrfTest('noob'))
            return $this->res->redirect($next);

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 3,
            'type'   => 'venue',
            'original' => $venue,
            'changes'  => null
        ]);

        Venue::remove(['id'=>$id]);

        $this->res->redirect($next);
    }
}
