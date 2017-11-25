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

        //$where_district = [];
        if(!is_null($area)) {
            $w_district = "district in ('".implode("','", explode(',', $area))."')";
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

	    if(!is_null($start) and !is_null($end)) {
                $price_limit[] = array(
                    'start'=> $start,
                    'end'=>$end
                );
	    }
        }
	

	$w_price = null;
	if(!is_null($price)) {
            $where_price = [];
            foreach($price_limit as $val) {
                $where_price[] = "price between ".$val['start'].' and '. $val['end'];
            }

     	    $w_price = implode(" or ", $where_price);
	}

        $where_huxing = [];
	$w_huxing = null;
        if(!is_null($huxing)) {
            $huxing = explode(",", $huxing);
            foreach($huxing as $val) {
                $where_huxing[] = "layout like '".$val."室%'";
            }
	    $w_huxing = implode(" or ", $where_huxing);
        }
	

        $where_flood = [];
	$w_flood = null;
        if(!is_null($flood)) {
            $flood = explode(",", $flood);
            foreach($flood as $val) {
                $where_flood[] = "flood like '".$val."楼%'";
            }
	    $w_flood = implode(" or ", $where_flood);
        }

	$where = '';
	if(!is_null($w_price)) {
	    $where .= '('.$w_price.') and ';
	}

        if($elevator) {
            $where .= "(elevator='有') and ";
        }

        if($subway) {
            $where .= "(tag like '%号线%') and ";
        }

        if(isset($w_district)) {
            $where .= "(".$w_district.") and ";
        }

        if($w_price) {
            $where .= "(".$w_price.") and ";
        }

        if($w_huxing) {
            $where .= "(".$w_huxing.") and ";
        }

        if($w_flood) {
            $where .= "(".$w_flood.") and ";
        }

        if(substr(trim($where), -3) == 'and'){
	    $where = substr(trim($where), 0, -3);
	}
	//dump($where);
	//die;
	$order_map = [
	    "价格"=> 'price',
	    "面积"=> 'area',
	    "小区均价"=> 'unit_price',
	    "关注人数"=> 'followed',
	    "带看人数"=> 'visited',
	    "发布时间"=> 'list_time'
	];
	

	$page_now = $request->input("page", 1);
	$per_page = 10;
	$sql = "select * from crawl_lianjia_house";
	$count_sql = 'select count(*) as cou from crawl_lianjia_house ';
	if($where) {
		$sql .= ' where '.$where;
		$count_sql .= ' where '.$where;
	}
	if(!is_null($order) and isset($order_map[$order])) {
	    $sql .= " order by ".$order_map[$order].' desc';
	}

	
	$all_count = DB::select($count_sql);
	$all_count = $all_count[0]->cou;
	$all_page = intval(ceil($all_count / $per_page));

	$sql .= ' limit '.($page_now-1)*$per_page.",".$per_page;
	$page = [
	    "now"=> $page_now,
	    "pre"=> $page_now - 1,
	    "next"=> $page_now + 1,
	    "last"=> $all_page
	];
	
	$houses = DB::select($sql);
	$url = trim('http://'.$_SERVER['HTTP_HOST'].(preg_replace("/page=\d+\&?/", "", $_SERVER['REQUEST_URI'])), "&");
	if(substr($url, -1) == '/'){
		$url = substr($url, 0, -1);
	}
	$url = $url."?_time=".time();
//        $houses = House::select('select * from lianjia_house where');
        //$houses = $houses->simplePaginate(15);
        return view('index/index', ['houses'=>$houses, "page"=>$page, "url"=>$url]);
    }

    public function img(Request $request) {
	$name = $request->input('name', null);
	$base_url = "https://image1.ljcdn.com/120000-inspection/";
	if(!is_null($name)) {
	    $img = file_get_contents($base_url.$name);
	    return response($img, 200, [
		'Content-Type' => 'image/png',
	    ]);
	}
	else{
	    return "图片地址不合法";
	}
    }
}
