<?php 
$settings = array();

/*###########################
#         Database          #
###########################*/
$settings['type'] = "mysqli";
$settings['host'] = "localhost";
$settings['user'] = "";
$settings['pass'] = "";
$settings['name'] = "";
$settings['tablePrefix'] = "";

/* Table Names */
$settings['access'] = "access";                    
$settings['badWord'] = "bad_word";
$settings['basketObject'] = "basket_object";
$settings['bugReport'] = "bug_report";
$settings['brands'] = "brands";
$settings['city'] = "city";
$settings['commentObject'] = "comment_object";
$settings['company'] = "company";
$settings['color'] = "color";
$settings['compareObject'] = "compare_object";
$settings['contactMessage'] = "contact_message";
$settings['countries'] = "countries";
$settings['district'] = "district";
$settings['distance'] = "distance";
$settings['directoryObject'] = "directory_object";
$settings['directoryCategory'] = "directory_category";
$settings['eDeliveryObject'] = "e_delivery_object";
$settings['eDeliveryType'] = "e_delivery_type";
$settings['eduBranch'] = "edu_branch";
$settings['faqObject'] = "faq_object";
$settings['financialBank'] = "financial_bank";
$settings['financialInvoice'] = "financial_invoice";
$settings['financialTransaction'] = "financial_transaction";
$settings['financialTransactionType'] = "financial_transaction_type";
$settings['formsEntity'] = "forms_entity";
$settings['formsFields'] = "forms_fields";
$settings['galleryCategory'] = 'gallery_category';
$settings['galleryObject'] = 'gallery_object';
$settings['gender'] = "gender";
$settings['groupManMembers'] = "group_man_members";
$settings['groupManObject'] = "group_man_object";
$settings['helpDeskEntity'] = "help_desk_entity";
$settings['humanResource'] = "human_resource";
$settings['jobTitle'] = "job_title";
$settings['lang'] = "lang";
$settings['langCode'] = "lang_code";
$settings['level'] = "level";
$settings['libraryCategory'] = "library_category";
$settings['libraryFavorite'] = "library_favorite";
$settings['libraryObject'] = "library_object";
$settings['mtaLog'] = "mta_log";
$settings['mtaQueue'] = "mta_queue";
$settings['mtaSmtp'] = "mta_smtp";
$settings['menu'] = "menu";
$settings['payPerClickCategory'] = "pay_per_click_category";
$settings['payPerClickObject'] = "pay_per_click_object";
$settings['pollEntity'] = "poll_entity";
$settings['pollStat'] = "poll_stat";
$settings['post'] = "post";
$settings['postCategory'] = "post_category";
$settings['postObject'] = "post_object";
$settings['productCategory'] = "product_category";
$settings['productObject'] = "product_object";
$settings['productObjectExtraFields'] = "product_object_extra_fields";
$settings['region'] = "region";
$settings['religion'] = "religion";
$settings['seo'] = "seo";
$settings['session'] = "session";
$settings['shopCategory'] = "shop_category";
$settings['shopObject'] = "shop_object";
$settings['smsCategory'] = "sms_category";
$settings['smsObject'] = "sms_object";
$settings['state'] = "state";
$settings['status'] = "status";
$settings['trustUrl'] = "trust_url";

$settings['usrsID'] = "usrs_ID";
$settings['usrsCo'] = "usrs_Co";
$settings['usrsBankAccounts'] = "usrs_Bank_Accounts";
$settings['usrsAddress'] = "usrs_Address";
$settings['usrsTel'] = "usrs_Tel";
$settings['usrsRelations'] = "usrs_Relations";
$settings['usrsPersonal'] = "usrs_Personal";
$settings['relType'] = "rel_Type";
$settings['addrTelType'] = "addr_Tel_Type";

$settings['userSettings'] = "user_settings";
$settings['watchDog'] = "watch_dog";
$settings['logger'] = "logger";


/*###########################
#         Addresses         #
###########################*/
$settings['kernelAddress'] = "kernel";
$settings['controllerAddress'] = "kernel/controller";
$settings['moduleController'] = "controller";
$settings['modelAddress'] = "model";
$settings['viewAddress'] = "view";
$settings['tplAddress'] = "tpl";
$settings['libraryAddress'] = "kernel/lib";
$settings['phpThumbLibraryAddress'] = "kernel/lib/xorg/phpThumb";
$settings['langAddress'] = "lang";
$settings['themeAddress'] = "theme";
$settings['docAddress'] = "doc";
$settings['moduleAddress'] = "module";
$settings['homeAddress'] = "home";
$settings['envAddress'] = "env";

/*###########################
#        Extentions         #
###########################*/
$settings['ext1'] = ".pom";
$settings['ext2'] = ".php";
$settings['ext3'] = ".txt";
$settings['ext4'] = ".htm";
$settings['ext5'] = ".tpl";

/*###########################
#        Information        #
###########################*/
$settings['siteName'] = "core";
$settings['websiteTitle'] = "core";
$settings['domainName'] = "";
$settings['packageName'] = "core";
$settings['trailerAddress'] = "modam/html/";
$settings['fullAddress'] = $settings['domainName'] . "/" . $settings['trailerAddress'];
$settings['version'] = "0.7.1.3";
$settings['keyWords'] = "core,php";
$settings['author'] = "Vahid Naderi";
$settings['authorWebsite'] = "http://www.idealmart.ir";
$settings['description'] = "";
$settings['robots'] = "INDEX,FOLLOW"; // ALL | NONE | INDEX | NOINDEX | FOLLOW | NOFOLLOW
$settings['copyright'] = "کلیه حقوق این سایت برای $settings[author] می باشد.";
$settings['address'] = "";

/*###########################
#           Pass            #
###########################*/
$settings['passLenght'] = 6;
$settings['userLenght'] = 6;

/*###########################
#           Mail            #
###########################*/
$settings['adminMail'] = "admin@" . $settings['domainName'];
$settings['invoiceMail'] = "invoice@" . $settings['domainName'];
$settings['infoMail'] = "info@" . $settings['domainName'];
$settings['sellMail'] = "sale@" . $settings['domainName'];
$settings['roboMail'] = "noreply@" . $settings['domainName'];
$settings['accountingMail'] = "accounting@" . $settings['domainName'];
$settings['supportMail'] = "support@" . $settings['domainName'];

/*###########################
#           Lang            #
###########################*/
$settings['langs'] = "1"; //Fa:1, En:2, Fr:3;
$settings['language'] = "fa-ir";

/*###########################
#           Theme           #
###########################*/
$settings['theme'] =  "core";

/*###########################
#     Sessions & Cookie     #
###########################*/
$settings['sessionTimeOut'] = 1200; //Second

/*###########################
#          Static           #
###########################*/
$settings['localTime'] = "+03:30";

/*###########################
#          Static           #
###########################*/
$settings['googleAccount'] = "";
$settings['googleDomainName'] = "";

/*###########################
#          Smarty           #
###########################*/
$settings['commonTpl'] = "theme/$settings[theme]/tpl/common/";
$settings['customiseTpl'] = "theme/$settings[theme]/tpl/customise/";
$settings['cache'] = false; //true (for host) or false (for local)
$settings['cacheLifeTime'] = 86400;  //  24 hours
$settings['cacheDir'] = "tmp/cache";

/*###########################
#          ContactUs        #
###########################*/
$settings['site'] = "";
$settings['tel'] = "";
$settings['fax'] = "";
$settings['supportTel'] = "";
$settings['smsTel'] = "";
$settings['weblog'] = "";
$settings['facebook'] = "";
$settings['stumble'] = "";
$settings['googlePlus'] = "";
$settings['twitter'] = "";
$settings['linkedin'] = "";
$settings['linkedinBadge'] = "";
$settings['linkedinBadge'] = "";
$settings['delicious'] = "";
$settings['deliciousBadge'] = "";
$settings['pinterest'] = "";

/*###########################
#          Default          #
###########################*/
$settings['defaultOp'] = "userMan";
$settings['defaultMode'] = "c_loginContent";

/*###########################
#          Debug           #
###########################*/
$settings['debug'] = "on";
$settings['debugMyFileWrite']="off";
$settings['chr'] = "on";
$settings['chrF'] = "on";
$settings['chrM'] = "on";
$settings['chrE'] = "on";
$settings['chrL'] = "on";
$settings['debugFile'] = "logs/Debug.log";

?>
