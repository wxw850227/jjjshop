<?php

namespace app\shop\controller\statistics;

use app\shop\controller\Controller;
use app\shop\service\statistics\DataService as StatisticsDataService;

/**
 * 数据统计控制器
 */
class Data extends Controller
{
    //数据概况服务类
    private $statisticsDataService;


    /**
     * 构造方法
     */
    public function initialize()
    {
        $this->statisticsDataService = new StatisticsDataService;
    }

    /**
     * 数据统计主页
     */
    public function index()
    {
        return $this->renderSuccess('', [
            // 数据概况
            'survey' => $this->statisticsDataService->getSurveyData(),
            // 商品销售榜
            'productRanking' => $this->statisticsDataService->getProductRanking(),
            // 用户消费榜
            'userExpendRanking' => $this->statisticsDataService->geUserExpendRanking(),
        ]);
    }

    /**
     * 数据概况API
     */
    public function survey($startDate = null, $endDate = null)
    {
        $times = $this->postData();
        $startDate = array_shift($times['times']);
        $endDate = array_pop($times['times']);
        return $this->renderSuccess('', $this->statisticsDataService->getSurveyData($startDate, $endDate));
    }

}