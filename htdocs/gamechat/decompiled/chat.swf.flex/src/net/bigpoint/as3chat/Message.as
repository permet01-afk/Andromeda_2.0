package net.bigpoint.as3chat 
{
    public class Message extends Object
    {
        public function Message(arg1:int, arg2:String, arg3:String, arg4:String)
        {
            super();
            this.messageType = arg1;
            if (arg2 != null) 
            {
                this.username = net.bigpoint.as3chat.Main.maskHTML(arg2);
            }
            if (arg3 != null) 
            {
                this.clanTag = net.bigpoint.as3chat.Main.maskHTML(arg3);
            }
            this.message = arg4;
            return;
        }

        public function getMessage():String
        {
            return this.message;
        }

        public function getHTML():String
        {
            var loc1:*=null;
            var loc2:*=null;
            var loc3:*=null;
            var loc4:*=null;
            if (this.messageType != TYPE_USER) 
            {
                if (this.messageType != TYPE_MODERATOR) 
                {
                    if (this.messageType != TYPE_SYSTEM) 
                    {
                        if (this.messageType != TYPE_WHISPER) 
                        {
                            if (this.messageType != TYPE_YOU_WHISPER) 
                            {
                                if (this.messageType != TYPE_INVITE) 
                                {
                                    if (this.messageType != TYPE_HELP) 
                                    {
                                        if (this.messageType == TYPE_GAMEHELP) 
                                        {
                                            loc1 = "<span class=\'system\'>" + net.bigpoint.as3chat.Main.maskHTML(this.message) + "</span><br>";
                                        }
                                    }
                                    else 
                                    {
                                        loc4 = this.message.split("@");
                                        loc1 = "<span class=\'system\'>" + loc4[0] + " " + "</span><span class=\'text\'>" + loc4[1] + "</span><br>";
                                    }
                                }
                                else 
                                {
                                    loc4 = net.bigpoint.as3chat.Main.maskHTML(this.message).split(";");
                                    loc1 = "<span class=\'link\'><a href=\"event:INVITE|" + loc4[1] + "\">" + loc4[0] + "</a></span><br>";
                                }
                            }
                            else 
                            {
                                loc1 = "<span class=\'system\'>" + this.username + "</span><span class=\'text\'>" + net.bigpoint.as3chat.Main.maskHTML(this.message) + "</span><br>";
                            }
                        }
                        else 
                        {
                            loc3 = this.username.split("WHISPER_SEPERATOR");
                            loc1 = "<span class=\'system\'><a href=\"event:USER|" + loc3[0] + "\">" + loc3[1] + "</a></span><span class=\'text\'>" + net.bigpoint.as3chat.Main.maskHTML(this.message) + "</span><br>";
                        }
                    }
                    else 
                    {
                        loc1 = "<span class=\'system\'>" + this.username + ": " + "</span><span class=\'text\'>" + this.message + "</span><br>";
                    }
                }
                else if (this.adminLevelId > -1 && this.adminLevelId < 3) 
                {
                    loc1 = "<span class=\'supporter\'><a href=\"event:USER|" + this.username + "\">" + this.username + ": </a> " + net.bigpoint.as3chat.Main.maskHTML(this.message) + "</span><br>";
                }
                else 
                {
                    loc1 = "<span class=\'mod\'><a href=\"event:USER|" + this.username + "\">" + this.username + ": </a> " + net.bigpoint.as3chat.Main.maskHTML(this.message) + "</span><br>";
                }
            }
            else 
            {
                if (this.clanTag == null) 
                {
                    loc2 = this.username;
                }
                else 
                {
                    loc2 = "[" + this.clanTag + "] " + this.username;
                }
                loc1 = "<span class=\'sender\'><a href=\'event:USER|" + this.username + "\'>" + loc2 + ": </a>" + "</span><span class=\'text\'>" + net.bigpoint.as3chat.Main.maskHTML(this.message) + "</span><br>";
            }
            return loc1;
        }

        public function getInvitedRoomId():int
        {
            return this.invitedRoomId;
        }

        public function setInvitedRoomId(arg1:int):void
        {
            this.invitedRoomId = arg1;
            return;
        }

        public function setAdminLevelId(arg1:int):void
        {
            this.adminLevelId = arg1;
            return;
        }

        
        {
            TYPE_USER = 0;
            TYPE_MODERATOR = 1;
            TYPE_SYSTEM = 2;
            TYPE_WHISPER = 3;
            TYPE_INVITE = 4;
            TYPE_HELP = 5;
            TYPE_YOU_WHISPER = 6;
            TYPE_GAMEHELP = 7;
            chatFontFace = "TAHOMA";
            chatFontSize = 10;
        }

        internal var message:String;

        internal var username:String;

        internal var clanTag:String;

        internal var invitedRoomId:int=-1;

        internal var messageType:int;

        internal var adminLevelId:int=-1;

        public static var TYPE_USER:int=0;

        public static var TYPE_MODERATOR:int=1;

        public static var TYPE_SYSTEM:int=2;

        public static var TYPE_WHISPER:int=3;

        public static var TYPE_INVITE:int=4;

        public static var TYPE_HELP:int=5;

        public static var TYPE_YOU_WHISPER:int=6;

        public static var TYPE_GAMEHELP:int=7;

        public static var chatFontFace:String="TAHOMA";

        public static var chatFontSize:int=10;

        public static var chatFontColor:String;
    }
}
