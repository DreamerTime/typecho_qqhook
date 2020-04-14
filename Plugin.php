<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
require dirname(__FILE__) . 'qq.php'
/**
 * Hello World
 * 
 * @package All2QQHookRobot 
 * @author kjun
 * @version 1.0.0
 * @link http://blog.kjun.wang
 */
class qqhook_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Feedback')->finishComment = array('qqhook_Plugin', 'comment_send');
        Typecho_Plugin::factory('Widget_Comments_Edit')->finishComment = array('qqhook_Plugin', 'comment_send');
        Typecho_Plugin::factory('Widget_Contents_Post_Edit')->finishPublish = array('qqhook_Plugin', 'comment_send');
        return _t('请配置此插件的 Token, 以使您的 QQ Hook 推送生效');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        /** 分类名称 */
        $token = new Typecho_Widget_Helper_Form_Element_Text('QQHookAPI', NULL, 'QQHookAPI', _t('QQHookAPI'));
        $form->addInput($token);
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function send_comment($comment, $post)
    {

    }

    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function send_comment($comment, $post)
    {
       $text = $comment->author . ' 在 "' . $comment->title . '"(#' . $comment->cid . ') 中说到: > ' . $comment->text . ' (#' . $comment->coid . ')';

        HOOK_ROBOT::setMsg("$text");

        HOOK_ROBOT::send();

    }
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function send_content($content, $post)
    {
        $cid = $request->get('cid','');

        if(empty($cid)){
            die('cid不能为空');
        }


        $request->setParams(
            array(
                'cid'=>$cid,
                'created'=>time()
            )
        );
        $this->widget('Widget_Archive@indexxiu', 'pageSize=1&type=post', 'cid=$cid')->to($content);
        $sc=$content->title();
        $msg="可爱的群友们,快来看".$sc."啦"

        HOOK_ROBOT::setMsg("$msg");

        HOOK_ROBOT::send();

    }
}
