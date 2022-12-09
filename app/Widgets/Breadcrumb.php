<?php namespace App\Widgets;

use App\Models\Permission;
use App\Repositories\Eloquent\PermissionRepository;
use Tree,Route;
use Teepluss\Theme\Theme;
use Teepluss\Theme\Widget;
use App\Models\NavCategory;

class Breadcrumb extends Widget {

    /**
     * Widget template.
     *
     * @var string
     */
    public $template = 'breadcrumb';

    /**
     * Watching widget tpl on everywhere.
     *
     * @var boolean
     */
    public $watch = false;

    /**
     * Arrtibutes pass from a widget.
     *
     * @var array
     */
    public $attributes = array();

    /**
     * Turn on/off widget.
     *
     * @var boolean
     */
    public $enable = true;

    /**
     * Code to start this widget.
     *
     * @return void
     */
    public function init(Theme $theme)
    {

    }

    /**
     * Logic given to a widget and pass to widget's view.
     *
     * @return array
     */
    public function run()
    {

        $route_name = Route::currentRouteName();
        $menu = app(PermissionRepository::class)->where('slug',$route_name)->first();
        if(!$menu)
        {
            $breadcrumbs = [];
        }
        else{
            $breadcrumbs = app(PermissionRepository::class)->permissionList($menu->id);
        }
        $count = count($breadcrumbs);

        $this->setAttribute('breadcrumbs',$breadcrumbs);
        $this->setAttribute('count',$count);

        $attrs = $this->getAttributes();

        return $attrs;
    }

}