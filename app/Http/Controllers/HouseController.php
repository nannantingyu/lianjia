<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HouseController extends Controller
{
    public function index(Request $request) {
        $area = $request->input('area', null);
        $price = $request->input('price', null);
        $order = $request->input('order', null);
        $huxing = $request->input('huxing', null);
        $flood = $request->input('flood', null);
        $elevator = $request->input('elevator', false);
        $subway = $request->input('subway', false);

        $where_district = [];
        if(!is_null($area)) {
            $where_district[] = "district in ('".implode("','", explode(',', $area))."')";
        }

        if(!is_null($price)) {
            $price = explode(',', $price);
            $all_price = [];

            $price_limit = [];
            foreach($price as $val) {
                $ex_price = explode('-', $val);
                $all_price = array_merge($all_price, $ex_price);
            }

            usort($all_price, function($a, $b){
               return intval($a) > intval($b);
            });

            $start = null;
            $end = null;
            foreach($all_price as $val) {
                if(is_null($start)) {
                    $start = $val;
                }
                elseif(is_null($end)) {
                    $end = $val;
                }
                elseif($val != $end) {
                    $price_limit[] = array(
                        'start'=>$start,
                        'end'=>$end
                    );

                    $start = $val;
                    $end = null;
                }
                elseif($val == $end) {
                    $end = null;
                }
            }
        }

        $price_limit[] = array(
            'start'=> $start,
            'end'=>$end
        );

        $where_price = [];
        foreach($price_limit as $val) {
            $where_price[] = "price between ".$val['start'].' and '. $val['end'];
        }

        $where_huxing = [];
        if(!is_null($huxing)) {
            $huxing = explode(",", $huxing);
            foreach($huxing as $val) {
                $where_huxing[] = "layout like '".$val."室%'";
            }
        }

        $where_flood = [];
        if(!is_null($flood)) {
            $flood = explode(",", $flood);
            foreach($flood as $val) {
                $where_flood[] = "flood like '".$val."楼%'";
            }
        }

        dump($where_huxing);
        if($elevator) {
            $where = "elevator='有' and (";
        }
        else {
            $where = '(';
        }

        if($subway) {
            $where .= "tag like '%号线%') and (";
        }

        if(!empty($where_district)) {
            $where .= implode(") or (", $where_district);
            $where .= ') and ((';
        }
        else {
            $where .= '(';
        }

        if(!empty($where_price)) {
            $where .= implode(") or (", $where_price);
            $where .= ')) and ((';
        }

        if(!empty($where_huxing)) {
            $where .= implode(') or (', $where_huxing);
            $where .= ')) and ((';
        }

        if(!empty($where_flood)) {
            $where .= implode(') or (', $where_flood);
        }

        $where .= "))";

        dump($where);
        dump('select * from lianjia_house where '.$where.' limit 10');
        $houses = DB::select('select * from crawl_lianjia_house where '.$where.' limit 10');

//        $houses = House::select('select * from lianjia_house where');
        dump($houses);
        die;
        $houses = $houses->simplePaginate(15);
        return view('index/index', ['houses'=>$houses]);
    }
}
