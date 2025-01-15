<?php
require ("info_bot.php");
define("APY_TOKEN_ETALL", $bot_token);
define("URL_TOKEN_ETALL", "https://api.telegram.org/bot" . APY_TOKEN_ETALL . "/");
function ETALL_REQUSTES($method, $paramsT)
{
    if (!$paramsT) {
        $paramsT = array();
    }
    $paramsT["method"] = $method;
    $CURL_SET = curl_init(URL_TOKEN_ETALL);
    curl_setopt($CURL_SET, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($CURL_SET, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($CURL_SET, CURLOPT_TIMEOUT, 10);
    curl_setopt($CURL_SET, CURLOPT_POSTFIELDS, json_encode($paramsT));
    curl_setopt($CURL_SET, CURLOPT_HTTPHEADER, array("Content-Type:application/json"));
    $END_CURL = curl_exec($CURL_SET);
    return $END_CURL;
}
$DOS_ETALL = file_get_contents("php://input");
$DOS_ETALL_Json = json_decode($DOS_ETALL, true);
$chat_id = $DOS_ETALL_Json["message"]["chat"]["id"];
$resss  = ETALL_REQUSTES("getMe" , null);
$resss_js = json_decode($resss , true);
$bot_name = $resss_js["result"]["username"];
$message_id = $DOS_ETALL_Json["message"]["message_id"];

$DETABES = new SQLite3("bot_chat_id.db");
$DETABES->exec("
    CREATE TABLE IF NOT EXISTS chat_id(
        chatmembr TEXT NOT NULL UNIQUE
    );
");
if ($chat_id == $chat_id_admin) {
    $btnf = array(
        "resize_keyboard"=>true,
        "keyboard"=>array(
            array("🌪 اطلاعات ربات"),
        )
    );

    if ($DOS_ETALL_Json["message"]["text"] == "/start"){
        ETALL_REQUSTES("sendMessage",array("chat_id"=>$chat_id,"text"=>"به پنل ادمین خوش آمدید" , "reply_markup"=>$btnf ));
    }
    if ($DOS_ETALL_Json["message"]["text"] == "🌪 اطلاعات ربات"){
        $quress = "SELECT * FROM chat_id";
        $RESALT = $DETABES->query($quress);
        $deta = [];
        while ($rows = $RESALT->fetchArray(SQLITE3_ASSOC)){
            $deta [] = $rows;
        }
        $membr = count($deta);
        $detas = date("Y"."-"."m"."-"."d");
        $text = "🤖 |- اطلاعات ربات: @$bot_name\n👤 |- تعداد کل کاربران: $membr\n🕰 |- آخرین آمار فعال در: $detas\n👰🏽‍♂️ |- تعداد ادمین ها: $chat_id_admin\n";
        ETALL_REQUSTES("sendMessage",array("chat_id"=>$chat_id,"text"=>$text, "reply_to_message_id" => $message_id));
    }
}else{
    if ($DOS_ETALL_Json["message"]["text"] == "/start"){
        $DETABES->exec("INSERT INTO chat_id(chatmembr) VALUES ('$chat_id')");
        ETALL_REQUSTES("setMyName",array("name"=>"چـت آیــבے گـرّام | 𝒸ℎ𝒶𝓉 𝒾𝒹 ℊ𝓇𝒶𝓂"));
        $btn = array(
            "resize_keyboard"=>true,
            "keyboard"=>array(
                array("دریافت اطلاعات کاربری من 👤"),
                array("⚙ help")
            )
        );
        $text = "♨ به ربات <u>چت آیدی گرام</u> خوش آمدید\n برای دریافت help ربات دستور /help را ارسال کنید 🧐 \n\n <blockquote><b>♨Welcome to <u>idgram chatbot</u></b>\nSend the command /help to get help from the robot 🧐</blockquote>";
        ETALL_REQUSTES("sendMessage",array("chat_id"=>$chat_id,"text"=>$text , "parse_mode"=>"HTML" , "reply_markup"=>$btn , "reply_to_message_id" => $message_id));
    }
    $btn_bot = array(
        "resize_keyboard"=>true,
        "inline_keyboard"=>array(
            array(
                array("text"=>"اشتراک گذاری","switch_inline_query"=>"$chat_id"),
                array("text"=>"ورود به کانال","url"=>"https://t.me/VOLHALTY"),
            )
        )
    );
    if (isset($DOS_ETALL_Json["message"])){
        if ($DOS_ETALL_Json["message"]["text"] == "دریافت اطلاعات کاربری من 👤"){
            $first_name = $DOS_ETALL_Json["message"]["chat"]["first_name"];
            $username = $DOS_ETALL_Json["message"]["chat"]["username"];
            $id = $DOS_ETALL_Json["message"]["chat"]["id"];
            $language_code = $DOS_ETALL_Json["message"]["from"]["language_code"];
            $texts = "<b>Your user information :</b>\n\n<blockquote>🆔 - id : $id\n⚡ - name : $first_name\n⚡ - username : $username\n⚡ - language : $language_code</blockquote>\n\n🤖 @$bot_name";
            ETALL_REQUSTES("sendMessage",array("chat_id"=>$chat_id,"text"=>$texts , "parse_mode"=>"HTML" , "reply_markup"=>$btn_bot, "reply_to_message_id" => $message_id));
        }
    }
    if (isset($DOS_ETALL_Json["message"]["forward_from"])){
        $forward_from_id = $DOS_ETALL_Json["message"]["forward_from"]["id"];
        $forward_from_last_name = $DOS_ETALL_Json["message"]["forward_from"]["last_name"];
        $forward_from_username = $DOS_ETALL_Json["message"]["forward_from"]["username"];
        $text = "<b>Your user information :</b>\n\n<blockquote>🆔 - id :$forward_from_id\n⚡ - name : $forward_from_last_name\n⚡ - username :@$forward_from_username</blockquote>\n\n🤖 @$bot_name";
        ETALL_REQUSTES("sendMessage",array("chat_id"=>$chat_id,"text"=>$text , "parse_mode"=>"HTML", "reply_markup"=>$btn_bot, "reply_to_message_id" => $message_id));
    }
    if (isset($DOS_ETALL_Json["message"]["forward_from_chat"])){
        $forward_from_chat_id = $DOS_ETALL_Json["message"]["forward_from_chat"]["id"];
        $forward_from_chat_title = $DOS_ETALL_Json["message"]["forward_from_chat"]["title"];
        $forward_from_chat_username = $DOS_ETALL_Json["message"]["forward_from_chat"]["username"];
        $text = "<b>Your user channel :</b>\n\n<blockquote>🆔 - id :$forward_from_chat_id\n⚡ - name : $forward_from_chat_title\n⚡ - username :@$forward_from_chat_username</blockquote>\n\n🤖 @$bot_name";
        ETALL_REQUSTES("sendMessage",array("chat_id"=>$chat_id,"text"=>$text , "parse_mode"=>"HTML", "reply_markup"=>$btn_bot, "reply_to_message_id" => $message_id));
    }
    if ($DOS_ETALL_Json["message"]["text"] == "⚙ help" || $DOS_ETALL_Json["message"]["text"] == "/help"){
        $text = "<b> دریافت آیدی عددی و اطلاعات کاربر</b>\n\n● آیدی عدد شما: بعد از زدن استارت قابل مشاهده است.\n● آیدی عددی شخص: یک پیام از شخص فوروارد کنید. (در صورتیکه فرد از قسمت تنظیمات، پیام هدایت شده‌ی خود را محدود نکرده باشد) \n● آیدی عددی کانال: یک پیام از کانال به ربات فوروارد کنید.";
        ETALL_REQUSTES("sendMessage",array("chat_id"=>$chat_id,"text"=>$text , "parse_mode"=>"HTML", "reply_markup"=>$btn_bot));
    }
}