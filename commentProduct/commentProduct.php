<?php

if(!defined ('_PS_VERSION_'))
    return false;

require_once(_PS_Module_DIR_ . "commentProduct/commentProductClass.php");
class CommentProduct extends Module\Prestashop\PrestaShop\Core\Module\WidgetIntarface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'commentProduct';
        $this->author = 'Savu Teodor-Andrei';
        $this->version = '1.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Product comment', array(), 'Modules.CommentProduct.Admin');
        $this->description = $this->trans('Allow store users to leave a comment for product', array(), 'Modules.CommentProduct.Admin');
        $this->ps_version_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:CommentProduct/views/templates/hook/CommentProduct.tpl';
    }

        public function renderWidget($hookname, array $configuration)
        {
            $this->smarty->assign($this->getWidgetVariables($hookname, $configuration));
            return $this->fetch($this->templateFile);
        }

            public function install()
            {
                return parent::install()
                    && $this->registerHook('displayFooterProduct');

                 Db::getInstance()->execute('
                    CREATE TABLE IF NOT EXISTS `'. _DB_PREFIX_ .'product_comment` (
                    `id_comment` INT UNSIGEND NOT NULL AUTO_INCREMENT,
                    `user_id` INT(10) NOT NULL,
                    `product_id` int(10) NOT NULL,
                    `comment` varchar(255) NOT NULL,
                    PRIMARY KEY (`id_comment`)
                    ) ENGINE=' . _MYSQL_ENGINE_ .' DEFAULT CHARSET=utf8;');
            }

            public function uninstall()
            {
                return parent::uninstall();
                Db::getInstance()->execute('DROP TABLE IF EXISTS `'. _DB_PREFIX_ .'product_comment`');
            }

        public function getWidgetVariables($hookname, array $configuration)
        {
            // handle form submission
            $message = "";

            if (Tools::isSubmit('comment')) {
                $commentProduct = new commentProductClass();

                $commentProduct->comment = Tools::getValue('comment');
                $commentProduct->product_id = Tools::getValue('id_product');
                $commentProduct->user_id = Tools::getValue('user_id');

                if($commentProduct->save())
                    $message = true;
                else{
                    $message = false;

                }

                print_r (Tools::getAllValue());
            }
            //Get the previous comments

            $sql = new DbQuery();
            $sql->select('*');
            $sql->from('product_comment', 'pc');
            $sql->innerJoin('customer', 'c', 'pc.user_id = c.id_customer');
            $sql->where('pc.product_id = '.(int)Tools::getValue('id_product'));
            $comments = Db::getInstance()->executeS('
            SELECT * FROM `'. _DB_PREFIX_ .'product_comment`
            WHERE product_id =' .(int)Tools::getValue('id_product'));

            print_r($comments);

            return array(
                'message' => "Hello, this product is great",
                'messageResult' => $message,
                'comments' => Db::getInstance()->execute($sql)
            );

        }
}