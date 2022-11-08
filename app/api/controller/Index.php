<?php

namespace app\api\controller;

use app\store\model\Coll as CollModel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use think\facade\Db;

/**
 * 默认控制器
 * Class Index
 * @package app\api\controller
 */
class Index
{
    public function index()
    {
        

    }


//    public  function export()
//    {
//
//        $filename = date('YmdHis');
//
//        //首先我们先定义号每个字段的含义  键是数据库里的字段名  值是含义
//        $field_title = [
//            'coll_id'   => '编号',
//            'goods_id'  => '商品id',
//            'goods_name'=> '商品名称',
//            'num'       => '数量',
//            'user_id'   => '用户id',
//            'mobile'    => '手机号',
//            'addtime'   => '时间',
//        ];
//
//        $where = [];
//
//        /*$keyword = $this->request->param('keyword','');
//
//        $mobile = $this->request->param('mobile','');
//
//        if(!empty($keyword)){
//
//            $where[] = ['yg.goods_name','like','%'.$keyword.'%'];
//
//        }
//
//        if(!empty($mobile)){
//
//            $where[] = ['yu.mobile','=',$mobile];
//
//        }
//
//        $betweenTime = $this->request->param('betweenTime','');
//
//        // 起止时间
//        if (!empty($betweenTime)) {
//            $times = between_time($betweenTime);
//            $where[] = ['c.addtime', '>=', $times['start_time']];
//            $where[] = ['c.addtime', '<', $times['end_time'] + 86400];
//        }*/
//
//        //然后根据所需导出的字段组成相应的结果集
//        $data = CollModel::alias('c')
//            ->field('c.coll_id,c.addtime,c.user_id,c.goods_id,count(c.goods_id) as num')
//            ->field('yu.mobile')
//            ->field('yg.goods_name')
//            ->where($where)
//            ->where('c.is_give',0)
//            ->leftJoin('yoshop_goods yg','c.goods_id=yg.goods_id')
//            ->leftJoin('yoshop_user yu','yu.user_id=c.user_id')
//            ->group('yu.user_id')
//            ->order('c.addtime','desc')
//            ->select()->toArray();
//
//        $field_res = [];
//        foreach($data as $c){
//
//            $fields = array_keys($c);
//
//            foreach($fields as $k=>$v){
//
//                $field_res[$k]['title'] = $field_title[$v];
//                $field_res[$k]['field'] = $v;
//
//            }
//
//        }
//
//
//        $spreadsheet = new Spreadsheet();
//        $sheet = $spreadsheet->getActiveSheet();
//        // sheet title
//        $sheet->setTitle($filename);
//        $startRow = 1; // 数据起始行
//        foreach ($data as $key => $value) {
//            foreach ($field_res as $k => $v) {
//                $column = Coordinate::stringFromColumnIndex($k + 1);
//                if ($key == 0) {
//                    $sheet->setCellValue($column . $startRow, $v['title']);
//                    $sheet->getColumnDimension($column)->setWidth(20);
//                }
//                $i = $key + 2; //表格是从2开始的
//                //dd($v);
//                $sheet->setCellValue($column . $i, $value[$v['field']]);
//            }
//        }
//
//        $fileName = urlencode($filename) . '.xlsx';
//
//        $filePath = $this->getExportPath();
//        // 保存到文件
//        try {
//            $writer = new Xlsx($spreadsheet);
//            $writer->save(public_path() . $filePath . $fileName);
//        } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
//            throwError($e->getMessage());
//        }
//
//        return $filePath . $fileName;
//    }
//
//    /**
//     * 获取导出的文件夹路径
//     * @return string
//     */
//    private function getExportPath(): string
//    {
//        $filePath = 'uploads/export/' . $this->getStoreId() . '/';
//        !is_dir($filePath) && mkdir($filePath, 0755, true);
//        return $filePath;
//    }
//
//
//    /**
//     * 获取当前操作的商城ID
//     * @return int|null
//     */
//    protected function getStoreId(): ?int
//    {
//        if (empty($this->storeId) && in_array(app_name(), ['store', 'api'])) {
//            $this->storeId = getStoreId();
//        }
//        return $this->storeId;
//    }
}