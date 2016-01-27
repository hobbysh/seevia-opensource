<?php
/*****************************************************************************
 * SV-Cart 查看短信
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
?>
    <p class="am-u-md-12">
        <?php echo $html->link("短信历史列表","/sms/histories/",array("class"=>"am-btn am-btn-xs am-btn-default am-radius am-fr"),false,false);?>
    </p>
<?php echo $form->create('SmsSendHistory',array('action'=>'histories_view/'));?>
    <div class="am-u-lg-3 am-u-md-3 am-u-sm-3  am-detail-menu" >
        <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
            <li><a href="#tablemain">短信详情</a></li>
        </ul>
    </div>
    <div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9  am-detail-view" id="accordion"  >
        <div id="tablemain" class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title">
                    短信详情
                </h4>
            </div>
            <div id="basic_information" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <table class="am-table">
                        <tr>
                            <th>手机号码</th>
                            <td><?php echo $data['SmsSendHistory']['phone'];?></td>
                        </tr>
                        <tr>
                            <th>短信内容</th>
                            <td>
                                <?php echo $data['SmsSendHistory']['content'];?>
                            </td>
                        </tr>
                        <tr>
                            <th>待发送时间</th>
                            <td>
                                <?php echo $data['SmsSendHistory']['send_date'];?>
                            </td>
                        </tr>
                        <tr>
                            <th>发送错误次数</th>
                            <td>
                                <?php echo $data['SmsSendHistory']['flag'];?>
                            </td>
                        </tr>
                        <tr>
                            <th>创建时间</th>
                            <td>
                                <?php echo $data['SmsSendHistory']['created'];?>
                            </td>
                        </tr>
                        <tr>
                            <th>修改时间</th>
                            <td>
                                <?php echo $data['SmsSendHistory']['modified'];?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php echo $form->end();?>