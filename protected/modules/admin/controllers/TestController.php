<?php
class TestController extends AdminController
{
    public function init()
    {
//         exit('exit');
    }
    
    public function actionRedis()
    {
        $key1 = 'user111';
        $key2 = 'user222';
        $key3 = 'user333';
        $data1 = array('cdcchen', 50, 'male');
        $data2 = array('chris', 40, 'famale');
        $data3 = array('dong', 30, 'male');
        
        $result1 = app()->redis->set($key1, $data1, 10);
        $result2 = app()->redis->set($key2, $data2, 20);
        $result3 = app()->redis->set($key3, $data3, 30);
        print_r($result1); echo '<br />';
        print_r($result2); echo '<br />';
        print_r($result3);
        echo '<hr />';

        $data = app()->redis->get($key1);
        print_r($data); echo '<br />';
        $data = app()->redis->get($key2);
        print_r($data); echo '<br />';
        $data = app()->redis->get($key3);
        print_r($data);
        echo '<hr />';
        
        $dump = app()->redis->dump($key1);
        print_r($dump); echo '<br />';
        $dump = app()->redis->dump($key2);
        print_r($dump); echo '<br />';
        $dump = app()->redis->dump($key3);
        print_r($dump);
        echo '<hr />';
        
        $keys = app()->redis->keys('wdz*');
        print_r($keys);
        echo '<hr />';
    }
    
    public function actionDel($page = 1, $count = 500)
    {
        exit;
        $criteria = new CDbCriteria();
        $criteria->limit = $count;
        $criteria->order = 'id asc';
        
        $models = Post1::model()->findAll($criteria);
        if (count($models) == 0)
            echo 'no more posts.<br />';
        
        foreach ($models as $model) {
            try {
                if ($model->delete())
                    echo 'del success.<br />';
                else
                    echo 'del failed.<br />';
            }
            catch (Exception $e) {
                echo $e->getMessage() . '<br />';
            }
        }
    }
    
    public function actionDeltags()
    {
        exit;
        $sql = "select DISTINCT tags from cd_post where tags != '' order by id asc";
        $rows = app()->getDb()->createCommand($sql)
            ->from('cd_post')
            ->queryColumn();
        
        $tags = array();
        foreach ($rows as $row) {
            $tags = array_merge($tags, explode(',', $row));
        }
        $tags = array_unique($tags);
        $tags = array_map('trim', $tags);
        
        $models = Tag::model()->findAll();
        $count1 = $count2 = 0;
        foreach ($models as $model) {
            if (!in_array($model->name, $tags)) {
                $model->delete() && $count1++;
            }
            else
                $count2++;
        }
        
        echo 'count1: ' . $count1 . '<br />';
        echo 'count2: ' . $count2 . '<br />';
            
    }

    public function actionWeight()
    {
        $temp = array(30=>1, 31=>1, 10=>2, 13=>0, 5=>3, 8=>0, 3=>4);
        $weights = array_filter($temp);
        asort($weights);
        
        /*
        $data = array();
        $start = 0;
        foreach ($weights as $key => $weight) {
            $data = array_merge($data, array_fill($start, $weight, $key));
            $start = $weight;
        }
        print_r($data);
        echo (int)$data[array_rand($data)];
        exit;
        */
        
        print_r($weights);
//         exit;
        $rand = mt_rand(0, array_sum($weights)-1);
        echo $rand . '<br />';exit;
        
        $num = 0;
        $index = null;
        foreach (temp as $i => $weight) {
            if ($weight < 1) continue;
            $num += $weight;
            if ($rand/$num < 1) {
                $index = $i;
                break;
            }
        }
        for ($i=0; $i<count($weights); $i++) {
            if ($weights[$i] == 0) continue;
            $num += $weights[$i];
            if ($rand/$num < 1) {
                $index = $i;
                break;
            }
        }
        echo $index . '<br />';
        
        exit;
    }

    public function actionException()
    {
        throw new CHttpException(500013,  'fuck you');
    }

    public function actionHeaders()
    {
//         var_dump(getallheaders());
        var_dump($_SERVER);
    }
}

