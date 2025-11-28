package net.bigpoint.as3chat 
{
    public class Constants extends Object
    {
        public function Constants()
        {
            super();
            return;
        }

        
        {
            MSG_SEPERATOR = "%";
            PARAM_SEPERATOR = "@";
            ATRIBUTE_SEPERATOR = "|";
            OBJECT_SEPERATOR = "}";
            LINE_SEPERATOR = "#";
            CMD_USER_MSG = "a";
            CMD_ADMIN_NOT_EXIST = "b";
            CMD_ADMIN_LOGIN_OK = "c";
            CMD_GET_ADMIN_ROOMLIST = "d";
            CMD_SET_ADMIN_ROOMLIST = "e";
            CMD_GET_ITEMLIST = "f";
            CMD_SET_ITEMLIST = "g";
            CMD_MOD_JOIN_ROOM = "h";
            CMD_MOD_JOIN_OK = "i";
            CMD_ADMIN_MSG = "j";
            CMD_SET_MOD_CHATCOLOR = "k";
            CMD_ADMIN_LOGIN = "l";
            CMD_USER_EXIT = "n";
            CMD_NEW_ITEM = "o";
            CMD_ITEM_EXIST = "p";
            CMD_ITEM_NOTFOUND = "q";
            CMD_UPDATE_ITEM = "r";
            CMD_DELETE_ITEM = "s";
            CMD_WRONG_PASSWORD = "t";
            CMD_NEW_BADWORD = "u";
            CMD_BADWORD_EXIST = "v";
            CMD_DELETE_BADWORD = "w";
            CMD_UPDATE_BADWORD = "x";
            CMD_NEW_ACCOUNT = "y";
            CMD_PASSWORD_CHANGE_FAILED = "z";
            CMD_ACCOUNT_EXIST = "aa";
            CMD_NO_VALID_STRING = "ab";
            ERROR_LONGNAME_TO_LONG = "ac";
            ERROR_LONGNAME_TO_SHORT = "ad";
            ERROR_SHORTNAME_TO_LONG = "ae";
            ERROR_SHORTNAME_TO_SHORT = "af";
            CMD_GET_LOG = "ag";
            CMD_SET_LOG = "ah";
            CMD_NO_LOG_FOUND = "ai";
            CMD_GET_LOG_UPDATE = "aj";
            CMD_SET_LOG_UPDATE = "ak";
            CMD_GET_BANNED_USER_ITEMS = "al";
            CMD_SET_BANNED_USER_ITEMS = "am";
            CMD_BANNLIST_RESULT_LIMIT = "an";
            CMD_NO_BANNED_USER_ITEMS = "ao";
            CMD_UPDATE_LOGIN_LOGOUT = "ar";
            CMD_KICK_USER = "as";
            CMD_BANN_USER = "at";
            CMD_USER_KICKED = "au";
            WRONG_USERID = "av";
            USER_ALREADY_BANNED = "aw";
            CMD_USER_BANNED = "ax";
            CMD_CHANGE_PASSWORD = "ay";
            CMD_UPDATE_ACCOUNT = "az";
            CMD_PASSWORD_CHANGED = "ba";
            CMD_REMOVE_BANNED_USER = "bb";
            CMD_DEBANN_USER = "bc";
            CMD_GET_BANN_LOG = "bd";
            CMD_SET_BANN_LOG = "be";
            CMD_WORD_EXIST = "bf";
            CMD_GET_BANN_LOG_UPDATE = "bg";
            CMD_SET_BANN_LOG_UPDATE = "bh";
            CMD_ADD_ROOM_ID = "bi";
            CMD_REMOVE_ROOM_ID = "bj";
            CMD_GET_USERLIST = "bk";
            CMD_SET_USERLIST = "bl";
            CMD_CLEAR_USERLIST = "bm";
            CMD_CREATE_PROJECT = "bn";
            CMD_UPDATE_PROJECT = "bo";
            CMD_DELETE_PROJECT = "bp";
            CMD_PROJECT_EXIST = "bq";
            CMD_UPDATE_ADMIN_ITEM = "br";
            CMD_UPDATE_ADMIN_LEVEL_ID = "bs";
            CMD_SETTINGS_SAVED = "bt";
            CMD_USER_LOGIN = "bu";
            CMD_USER_LOGIN_OK = "bv";
            CMD_WRONG_CHAT_VERSION = "bw";
            CMD_GET_USER_ROOMLIST = "bx";
            CMD_SET_USER_ROOMLIST = "by";
            CMD_USER_JOIN = "bz";
            CMD_USER_ENTER_ROOM = "ca";
            CMD_USER_KICKED_BY_SYSTEM = "cb";
            CMD_USER_BANNED_BY_SYSTEM = "cc";
            CMD_KICK_BY_SYSTEM = "cd";
            CMD_BANN_BY_SYSTEM = "ce";
            CMD_NO_MORE_PRIVATE_ROOMS_ALLOWED = "cf";
            CMD_CREATE_ROOM_WRONG_ARGUMENTS = "cg";
            CMD_ROOMNAME_TOO_SHORT = "ch";
            CMD_PRIVATE_ROOM_EXIST = "ci";
            CMD_PRIVATE_ROOM_CREATED = "cj";
            CMD_ROOM_DELETED = "ck";
            CMD_PRIVATE_ROOM_NOT_EXIST = "cl";
            CMD_WRONG_COMMAND = "cm";
            CMD_CANNOT_INVITE_YOURSELF = "cn";
            CMD_INVITE_ERROR_NOT_YOUR_ROOM = "co";
            CMD_INVITE_ERROR_USER_NOT_FOUND = "cp";
            CMD_SHOW_INVITE_MESSAGE = "cq";
            CMD_YOU_INVITED = "cr";
            CMD_NO_WHISPER_MESSAGE = "cs";
            CMD_USER_NOT_EXIST = "ct";
            CMD_CANNOT_WHISPER_YOURSELF = "cu";
            CMD_USER_WHISPERS = "cv";
            CMD_YOU_WHISPER = "cw";
            CMD_LEAVE_ROOM = "cx";
            CMD_LEAVE_ROOM_OK = "cy";
            CMD_DELETE_ACCOUNT = "cz";
            CMD_FLOOD_WARNING = "da";
            CMD_SHOW_BANN_MESSAGE = "db";
            CMD_UPDATE_ONLINE_STATUS = "dc";
            CMD_ADMIN_ENTER_ROOM = "dd";
            CMD_ADMIN_LEAVE_ROOM = "de";
            CMD_SHOW_USERS = "df";
            CMD_SHOW_MODERATORS = "dg";
            CMD_LOGOUT_MODERATOR = "dh";
            CMD_LOGOUT_MODERATOR_OK = "di";
            CMD_GET_SERVER_STATISTICS = "dj";
            CMD_SET_SERVER_STATISTICS = "dk";
            CMD_CREATE_ROOM = "dl";
            CMD_INVALID_ROOM_NAME = "dm";
            CMD_COMPANY_ID_NOT_AVAILABLE = "dn";
            CMD_ROOM_CREATED = "do";
            CMD_DELETE_ROOM = "dp";
            CMD_DEVELOPER_MSG = "dq";
            CMD_ADD_FAVORITE_ROOM_ID = "dr";
            CMD_REMOVE_FAVORITE_ROOM_ID = "ds";
            CMD_COPY_INSTANCE = "dt";
            CMD_NEW_TEXTMODULE = "du";
            CMD_DELETE_TEXTMODULE = "dv";
            CMD_TEXTMODULE_CREATED = "dw";
            CMD_TEXTMODULE_DELETED = "dx";
            CMD_DELETE_PRIVATE_ROOM = "dy";
            CMD_CANNOT_LEAVE_ROOM = "dz";
            CMD_USER_JOIN_INVITED_ROOM = "ea";
            CMD_USER_LEFT_INVITED_ROOM = "eb";
            CMD_NOT_ROOM_OWNER = "ec";
            CMD_ROOMNAME_TOO_LONG = "ed";
            CMD_SET_OFFLINE_BANN_LOG = "ee";
            CMD_GET_USER_LOG = "ef";
            CMD_SET_USER_LOG = "eg";
            CMD_GET_MISSING_USER = "eh";
            CMD_SET_MISSING_USER = "ei";
            CMD_USER_LOGIN_NOK = "ej";
            CMD_PING = "ek";
            CMD_PONG = "el";
            CMD_HACK_ATTACK_DETECTED = "em";
            CMD_SHOW_RESTART_WINDOW = "en";
            CMD_GET_ACCOUNT_INFORMATION = "eo";
            CMD_SET_ACCOUNT_INFORMATION = "ep";
            CMD_PRIVATE_MOD_MSG = "eq";
            CMD_END_PRIVATE_MOD_SESSION = "er";
            CMD_GET_FILTERED_LOG = "es";
            CMD_SET_FILTERED_LOG = "et";
            CMD_SET_IP_BANNS = "eu";
            CMD_DEBANN_IP = "ev";
            CMD_REMOVE_BANNED_IP = "ew";
            CMD_AUTH_FAILED = "ex";
            CMD_IP_FAILED = "ey";
            CMD_CHAT_MINIMIZED = "ez";
            CMD_CHAT_MAXIMIZED = "fa";
            CMD_UPDATE_WORD_TO_GAME_ITEMS = "fb";
            CREATE_GROUP_ROOM = "fc";
            CMD_GROUP_ROOM_CREATED = "fd";
            CMD_REMOVE_GROUP_ROOM = "fe";
            CMD_LEAVE_GROUP_ROOM = "ff";
            CMD_GET_GAMEHELP = "fg";
            CMD_SET_GAMEHELP = "fh";
            CMD_UPDATE_GAMEHELPITEMS = "fi";
            CMD_SET_GAMEHELPITEM_STATUS = "fj";
            CMD_SCHEDULED_MSG = "fk";
            CMD_USER_ADDED_TO_DYN_ROOM = "fq";
            CMD_USER_REMOVED_FROM_DYN_ROOM = "fr";
            CMD_USER_JOINED_DYN_ROOM = "fs";
            CMD_USER_LEFT_DYN_ROOM = "ft";
            CMD_SCAL_JOINED_CHILD = "fu";
            CMD_CREATE_PRIVATE_ROOM_WITH_USERS = "fv";
            CMD_DYN_ROOM_CREATED = "fy";
            CMD_SECTOR_ROOM_UPDATE = "fz";
            CMD_DYN_ROOM_REMOVED = "ga";
            CMD_ROOM_NAME_NOT_ALLOWED = "gb";
            CMD_CREATE_CLAN_ROOM = "gw";
            CMD_CREATE_CLAN_ROOM_CREATED = "gx";
            ITEM_TYPE_GAME = 0;
            ITEM_TYPE_INSTANCE = 1;
            ITEM_TYPE_LANGUAGE = 2;
            ITEM_TYPE_BADWORD = 3;
            ITEM_TYPE_ACCOUNT = 4;
            ITEM_TYPE_PROJECT = 5;
            LONGNAME_EXIST = 6;
            SHORTNAME_EXIST = 7;
            ACCOUNT_EXIST = 8;
            CLIENT_TYPE_AS2 = 0;
            CLIENT_TYPE_AS3 = 1;
            CLIENT_TYPE_JAVA = 2;
        }

        public static var PARAM_SEPERATOR:String="@";

        public static var ATRIBUTE_SEPERATOR:String="|";

        public static var OBJECT_SEPERATOR:String="}";

        public static var LINE_SEPERATOR:String="#";

        public static var CMD_USER_MSG:String="a";

        public static var CMD_ADMIN_NOT_EXIST:String="b";

        public static var CMD_ADMIN_LOGIN_OK:String="c";

        public static var CMD_GET_ADMIN_ROOMLIST:String="d";

        public static var CMD_SET_ADMIN_ROOMLIST:String="e";

        public static var CMD_GET_ITEMLIST:String="f";

        public static var CMD_SET_ITEMLIST:String="g";

        public static var CMD_MOD_JOIN_ROOM:String="h";

        public static var CMD_MOD_JOIN_OK:String="i";

        public static var CMD_ADMIN_MSG:String="j";

        public static var CMD_SET_MOD_CHATCOLOR:String="k";

        public static var CMD_ADMIN_LOGIN:String="l";

        public static var CMD_USER_EXIT:String="n";

        public static var CMD_NEW_ITEM:String="o";

        public static var CMD_ITEM_EXIST:String="p";

        public static var CMD_ITEM_NOTFOUND:String="q";

        public static var CMD_UPDATE_ITEM:String="r";

        public static var CMD_DELETE_ITEM:String="s";

        public static var CMD_WRONG_PASSWORD:String="t";

        public static var CMD_NEW_BADWORD:String="u";

        public static var CMD_BADWORD_EXIST:String="v";

        public static var CMD_DELETE_BADWORD:String="w";

        public static var CMD_UPDATE_BADWORD:String="x";

        public static var CMD_NEW_ACCOUNT:String="y";

        public static var CMD_PASSWORD_CHANGE_FAILED:String="z";

        public static var CMD_ACCOUNT_EXIST:String="aa";

        public static var CMD_NO_VALID_STRING:String="ab";

        public static var ERROR_LONGNAME_TO_LONG:String="ac";

        public static var ERROR_LONGNAME_TO_SHORT:String="ad";

        public static var ERROR_SHORTNAME_TO_LONG:String="ae";

        public static var ERROR_SHORTNAME_TO_SHORT:String="af";

        public static var CMD_GET_LOG:String="ag";

        public static var CMD_SET_LOG:String="ah";

        public static var CMD_NO_LOG_FOUND:String="ai";

        public static var CMD_GET_LOG_UPDATE:String="aj";

        public static var CMD_SET_LOG_UPDATE:String="ak";

        public static var CMD_GET_BANNED_USER_ITEMS:String="al";

        public static var CMD_SET_BANNED_USER_ITEMS:String="am";

        public static var CMD_BANNLIST_RESULT_LIMIT:String="an";

        public static var CMD_NO_BANNED_USER_ITEMS:String="ao";

        public static var CMD_UPDATE_LOGIN_LOGOUT:String="ar";

        public static var CMD_KICK_USER:String="as";

        public static var CMD_BANN_USER:String="at";

        public static var CMD_USER_KICKED:String="au";

        public static var WRONG_USERID:String="av";

        public static var USER_ALREADY_BANNED:String="aw";

        public static var CMD_USER_BANNED:String="ax";

        public static var CMD_CHANGE_PASSWORD:String="ay";

        public static var CMD_UPDATE_ACCOUNT:String="az";

        public static var CMD_PASSWORD_CHANGED:String="ba";

        public static var CMD_REMOVE_BANNED_USER:String="bb";

        public static var CMD_DEBANN_USER:String="bc";

        public static var CMD_GET_BANN_LOG:String="bd";

        public static var CMD_SET_BANN_LOG:String="be";

        public static var CMD_WORD_EXIST:String="bf";

        public static var CMD_GET_BANN_LOG_UPDATE:String="bg";

        public static var CMD_SET_BANN_LOG_UPDATE:String="bh";

        public static var CMD_ADD_ROOM_ID:String="bi";

        public static var CMD_REMOVE_ROOM_ID:String="bj";

        public static var CMD_GET_USERLIST:String="bk";

        public static var CMD_SET_USERLIST:String="bl";

        public static var CMD_CLEAR_USERLIST:String="bm";

        public static var CMD_CREATE_PROJECT:String="bn";

        public static var CMD_UPDATE_PROJECT:String="bo";

        public static var CMD_DELETE_PROJECT:String="bp";

        public static var CMD_PROJECT_EXIST:String="bq";

        public static var CMD_UPDATE_ADMIN_ITEM:String="br";

        public static var CMD_UPDATE_ADMIN_LEVEL_ID:String="bs";

        public static var CMD_SETTINGS_SAVED:String="bt";

        public static var CMD_USER_LOGIN:String="bu";

        public static var CMD_USER_LOGIN_OK:String="bv";

        public static var CMD_WRONG_CHAT_VERSION:String="bw";

        public static var CMD_GET_USER_ROOMLIST:String="bx";

        public static var CMD_SET_USER_ROOMLIST:String="by";

        public static var CMD_USER_JOIN:String="bz";

        public static var CMD_USER_ENTER_ROOM:String="ca";

        public static var CMD_USER_KICKED_BY_SYSTEM:String="cb";

        public static var CMD_USER_BANNED_BY_SYSTEM:String="cc";

        public static var CMD_KICK_BY_SYSTEM:String="cd";

        public static var CMD_BANN_BY_SYSTEM:String="ce";

        public static var CMD_NO_MORE_PRIVATE_ROOMS_ALLOWED:String="cf";

        public static var CMD_CREATE_ROOM_WRONG_ARGUMENTS:String="cg";

        public static var CMD_ROOMNAME_TOO_SHORT:String="ch";

        public static var CMD_PRIVATE_ROOM_EXIST:String="ci";

        public static var CMD_PRIVATE_ROOM_CREATED:String="cj";

        public static var CMD_ROOM_DELETED:String="ck";

        public static var CMD_PRIVATE_ROOM_NOT_EXIST:String="cl";

        public static var CMD_WRONG_COMMAND:String="cm";

        public static var CMD_CANNOT_INVITE_YOURSELF:String="cn";

        public static var CMD_INVITE_ERROR_NOT_YOUR_ROOM:String="co";

        public static var CMD_INVITE_ERROR_USER_NOT_FOUND:String="cp";

        public static var CMD_SHOW_INVITE_MESSAGE:String="cq";

        public static var CMD_YOU_INVITED:String="cr";

        public static var CMD_NO_WHISPER_MESSAGE:String="cs";

        public static var CMD_USER_NOT_EXIST:String="ct";

        public static var CMD_CANNOT_WHISPER_YOURSELF:String="cu";

        public static var CMD_USER_WHISPERS:String="cv";

        public static var CMD_YOU_WHISPER:String="cw";

        public static var CMD_LEAVE_ROOM:String="cx";

        public static var CMD_LEAVE_ROOM_OK:String="cy";

        public static var CMD_DELETE_ACCOUNT:String="cz";

        public static var CMD_FLOOD_WARNING:String="da";

        public static var CMD_SHOW_BANN_MESSAGE:String="db";

        public static var CMD_UPDATE_ONLINE_STATUS:String="dc";

        public static var CMD_ADMIN_ENTER_ROOM:String="dd";

        public static var CMD_ADMIN_LEAVE_ROOM:String="de";

        public static var CMD_SHOW_USERS:String="df";

        public static var CMD_SHOW_MODERATORS:String="dg";

        public static var CMD_LOGOUT_MODERATOR:String="dh";

        public static var CMD_LOGOUT_MODERATOR_OK:String="di";

        public static var CMD_GET_SERVER_STATISTICS:String="dj";

        public static var CMD_SET_SERVER_STATISTICS:String="dk";

        public static var CMD_CREATE_ROOM:String="dl";

        public static var CMD_INVALID_ROOM_NAME:String="dm";

        public static var CMD_COMPANY_ID_NOT_AVAILABLE:String="dn";

        public static var CMD_ROOM_CREATED:String="do";

        public static var CMD_DELETE_ROOM:String="dp";

        public static var CMD_DEVELOPER_MSG:String="dq";

        public static var CMD_ADD_FAVORITE_ROOM_ID:String="dr";

        public static var CMD_REMOVE_FAVORITE_ROOM_ID:String="ds";

        public static var CMD_COPY_INSTANCE:String="dt";

        public static var CMD_NEW_TEXTMODULE:String="du";

        public static var CMD_DELETE_TEXTMODULE:String="dv";

        public static var CMD_TEXTMODULE_CREATED:String="dw";

        public static var CMD_TEXTMODULE_DELETED:String="dx";

        public static var CMD_DELETE_PRIVATE_ROOM:String="dy";

        public static var CMD_CANNOT_LEAVE_ROOM:String="dz";

        public static var CMD_USER_JOIN_INVITED_ROOM:String="ea";

        public static var CMD_USER_LEFT_INVITED_ROOM:String="eb";

        public static var CMD_NOT_ROOM_OWNER:String="ec";

        public static var CMD_ROOMNAME_TOO_LONG:String="ed";

        public static var CMD_SET_OFFLINE_BANN_LOG:String="ee";

        public static var CMD_GET_USER_LOG:String="ef";

        public static var CMD_SET_USER_LOG:String="eg";

        public static var CMD_GET_MISSING_USER:String="eh";

        public static var CMD_SET_MISSING_USER:String="ei";

        public static var CMD_USER_LOGIN_NOK:String="ej";

        public static var CMD_PING:String="ek";

        public static var CMD_PONG:String="el";

        public static var CMD_HACK_ATTACK_DETECTED:String="em";

        public static var MSG_SEPERATOR:String="%";

        public static var CMD_GET_ACCOUNT_INFORMATION:String="eo";

        public static var CMD_SET_ACCOUNT_INFORMATION:String="ep";

        public static var CMD_PRIVATE_MOD_MSG:String="eq";

        public static var CMD_END_PRIVATE_MOD_SESSION:String="er";

        public static var CMD_GET_FILTERED_LOG:String="es";

        public static var CMD_SET_FILTERED_LOG:String="et";

        public static var CMD_SET_IP_BANNS:String="eu";

        public static var CMD_DEBANN_IP:String="ev";

        public static var CMD_REMOVE_BANNED_IP:String="ew";

        public static var CMD_AUTH_FAILED:String="ex";

        public static var CMD_IP_FAILED:String="ey";

        public static var CMD_CHAT_MINIMIZED:String="ez";

        public static var CMD_CHAT_MAXIMIZED:String="fa";

        public static var CMD_UPDATE_WORD_TO_GAME_ITEMS:String="fb";

        public static var CREATE_GROUP_ROOM:String="fc";

        public static var CMD_GROUP_ROOM_CREATED:String="fd";

        public static var CMD_REMOVE_GROUP_ROOM:String="fe";

        public static var CMD_LEAVE_GROUP_ROOM:String="ff";

        public static var CMD_GET_GAMEHELP:String="fg";

        public static var CMD_SET_GAMEHELP:String="fh";

        public static var CMD_UPDATE_GAMEHELPITEMS:String="fi";

        public static var CMD_SET_GAMEHELPITEM_STATUS:String="fj";

        public static var CMD_SCHEDULED_MSG:String="fk";

        public static var CMD_SHOW_RESTART_WINDOW:String="en";

        public static var CMD_USER_REMOVED_FROM_DYN_ROOM:String="fr";

        public static var CMD_USER_JOINED_DYN_ROOM:String="fs";

        public static var CMD_USER_LEFT_DYN_ROOM:String="ft";

        public static var CMD_SCAL_JOINED_CHILD:String="fu";

        public static var CMD_CREATE_PRIVATE_ROOM_WITH_USERS:String="fv";

        public static var CMD_DYN_ROOM_CREATED:String="fy";

        public static var CMD_SECTOR_ROOM_UPDATE:String="fz";

        public static var CMD_DYN_ROOM_REMOVED:String="ga";

        public static var CMD_ROOM_NAME_NOT_ALLOWED:String="gb";

        public static var CMD_CREATE_CLAN_ROOM:String="gw";

        public static var CMD_CREATE_CLAN_ROOM_CREATED:String="gx";

        public static var ITEM_TYPE_GAME:Number=0;

        public static var ITEM_TYPE_INSTANCE:Number=1;

        public static var ITEM_TYPE_LANGUAGE:Number=2;

        public static var ITEM_TYPE_BADWORD:Number=3;

        public static var ITEM_TYPE_ACCOUNT:Number=4;

        public static var ITEM_TYPE_PROJECT:Number=5;

        public static var LONGNAME_EXIST:Number=6;

        public static var SHORTNAME_EXIST:Number=7;

        public static var ACCOUNT_EXIST:Number=8;

        public static var CLIENT_TYPE_AS2:Number=0;

        public static var CLIENT_TYPE_AS3:Number=1;

        public static var CLIENT_TYPE_JAVA:Number=2;

        public static var CMD_USER_ADDED_TO_DYN_ROOM:String="fq";
    }
}
