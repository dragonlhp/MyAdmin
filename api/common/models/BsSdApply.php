<?php
/**
 * Copyright (c) 2016, SILK Software
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 *    This product includes software developed by the SILK Software.
 * 4. Neither the name of the SILK Software nor the
 *   names of its contributors may be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY SILK Software ''AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL SILK Software BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * Created by PhpStorm.
 * User: Bob song <bob.song@silksoftware.com>
 * Date: 16-11-9
 * Time: 13:58
 */

namespace common\models;

use Yii;

class BsSdApply extends \yii\db\ActiveRecord
{
    /**
     * get db table name
     * @inheritdoc
     * @return string
     */
    public static function tableName()
    {
        return 'bs_sd_apply';
    }


    /**
     * set field rules
     * @return array
     */
    public function rules()
    {
        return array(
//            array('email','email'),
//            array(array('mobile','link_mobile'),'length' =>array(11,11)),
            //           array(array('link_tel'),'number'),
//            array(array('product','innovate','development','science','collaborate'),'length' =>array(0,2000)),
        );
    }
}