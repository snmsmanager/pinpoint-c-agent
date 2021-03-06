<?php
/**
 * Copyright 2020 NAVER Corp.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Plugins;
use Plugins\Candy;

/**
 * @hook:think\db\PDOConnection::getPDOStatement
 */
class DBPlugin extends Candy
{
    /**
     * @hook:think\db\Builder::__construct
     */
    public function onBefore()
    {
        if(strpos($this->apId,"__construct")!== false)
        {
            $config = $this->args[0]->getConfig();
            if(isset($config['type']))
            {
                $type = $config['type'];

                switch ($type){
                    case "mysql":
                        pinpoint_add_clue(SERVER_TYPE,MYSQL);
                        break;
                    default:
                        break;
                }
                $url = $config['database'];
                pinpoint_add_clue(DESTINATION,$url);
            }
            return ;
        }
        pinpoint_add_clues(PHP_ARGS,$this->args[0]);
    }

    public function onEnd(&$ret)
    {
        pinpoint_add_clues(PHP_RETURN,"...");
    }

    public function onException($e)
    {

    }
}