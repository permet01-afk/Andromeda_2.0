package net.bigpoint.as3chat 
{
    import flash.display.*;
    import flash.events.*;
    import flash.geom.*;
    import flash.net.*;
    import flash.system.*;
    import flash.text.*;
    import flash.utils.*;
    import mx.utils.*;
    import net.bigpoint.flashcorelib.resources.*;
    
    public class Main extends flash.display.MovieClip
    {
        public function Main()
        {
            this.roomList = new Array();
            this.companyRoomIds = new Array();
            this.ignoredUsers = new Array();
            super();
            flash.system.Security.allowDomain("*");
            return;
        }

        public function userIsIgnored(arg1:String):Boolean
        {
            var loc1:*=0;
            while (loc1 < this.ignoredUsers.length) 
            {
                if (arg1.toLowerCase() == this.ignoredUsers[loc1]) 
                {
                    return true;
                }
                ++loc1;
            }
            return false;
        }

        public function allowUser(arg1:String):void
        {
            var loc1:*=0;
            while (loc1 < this.ignoredUsers.length) 
            {
                if (arg1.toLowerCase() == this.ignoredUsers[loc1]) 
                {
                    this.ignoredUsers.splice(loc1, 1);
                    break;
                }
                ++loc1;
            }
            return;
        }

        public function getRoomList():Array
        {
            return this.roomList;
        }

        public function getActiveRoom():net.bigpoint.as3chat.Room
        {
            var loc2:*=null;
            var loc1:*=0;
            while (loc1 < this.roomList.length) 
            {
                loc2 = this.roomList[loc1];
                if (loc2.getRoomId() == this.activeRoomId) 
                {
                    return loc2;
                }
                ++loc1;
            }
            return null;
        }

        public function getRoom(arg1:int):net.bigpoint.as3chat.Room
        {
            var loc2:*=null;
            var loc1:*=0;
            while (loc1 < this.roomList.length) 
            {
                loc2 = this.roomList[loc1];
                if (loc2.getRoomId() == arg1) 
                {
                    return loc2;
                }
                ++loc1;
            }
            return null;
        }

        public function getActiveThemeId():int
        {
            return this.activeThemeId;
        }

        public function setActiveThemeId(arg1:int):void
        {
            this.activeThemeId = arg1;
            return;
        }

        public function getActiveRoomId():int
        {
            return this.activeRoomId;
        }

        public function setActiveRoomId(arg1:int):void
        {
            this.activeRoomId = arg1;
            return;
        }

        public function getActiveScalRoomId():int
        {
            return this.activeScalRoomId;
        }

        public function setActiveScalRoomId(arg1:int):void
        {
            this.activeScalRoomId = arg1;
            return;
        }

        public function getActiveNormalRoomId():int
        {
            return this.activeNormalRoomId;
        }

        public function setActiveNormalRoomId(arg1:int):void
        {
            this.activeNormalRoomId = arg1;
            return;
        }

        public function getCss():flash.text.StyleSheet
        {
            return this.css;
        }

        public function isInCompanyIdList(arg1:int):Boolean
        {
            var loc1:*=0;
            while (loc1 < this.companyRoomIds.length) 
            {
                if (arg1 == this.companyRoomIds[loc1]) 
                {
                    return true;
                }
                ++loc1;
            }
            return false;
        }

        public function onIOError(arg1:flash.events.IOErrorEvent):void
        {
            return;
        }

        internal function deleteRoom(arg1:Number):void
        {
            var loc2:*=null;
            var loc1:*=0;
            while (loc1 < this.roomList.length) 
            {
                loc2 = this.roomList[loc1];
                if (loc2.getRoomId() == arg1) 
                {
                    this.roomList.splice(loc1, 1);
                    break;
                }
                ++loc1;
            }
            return;
        }

        public function getChatVersion():String
        {
            return this.chatVersion;
        }

        public function getUserId():int
        {
            return this.userId;
        }

        public function joinInvitedRoom(arg1:int):void
        {
            var loc1:*=this.getRoom(arg1);
            if (loc1 == null) 
            {
                this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.PrivateRoomNotExist"), this.activeRoomId);
                return;
            }
            loc1.setInvited(true);
            this.chat.updateTabBar();
            this.chat.selectTabIndexByRoomId(loc1.getRoomId());
            var loc2:*=new Array();
            loc2.push(loc1.getRoomId());
            this.sendCommand(net.bigpoint.as3chat.Constants.CMD_USER_JOIN, loc2);
            return;
        }

        public function getViewportBounds():flash.geom.Rectangle
        {
            return this._bounds;
        }

        public function setViewportBounds(arg1:flash.geom.Rectangle):void
        {
            this._bounds = arg1;
            return;
        }

        public function getSaveLastPosition():Boolean
        {
            return this.saveLastPosition;
        }

        public function getSharedObject():flash.net.SharedObject
        {
            return this.sharedObject;
        }

        public function getPosition():flash.geom.Point
        {
            var loc1:*=this.chat.getBounds(this.stage);
            var loc2:*=new flash.geom.Point(loc1.x, loc1.y);
            return loc2;
        }

        public function getSize():flash.geom.Point
        {
            var loc1:*=new flash.geom.Point(this.chat.getResizer().x, this.chat.getResizer().y);
            return loc1;
        }

        public function setSize(arg1:int, arg2:int):void
        {
            if (this.chat != null) 
            {
                this.chat.setSize(arg1, arg2);
            }
            else 
            {
                this.size = new flash.geom.Point(arg1, arg2);
            }
            return;
        }

        public function setRestoreButtonPosition(arg1:int, arg2:int):void
        {
            if (this.chat.isMinimizeButtonVisible()) 
            {
                if (this.chat.isBtnRestoreVisible()) 
                {
                    this.chat.getBtnRestore().x = arg1;
                    this.chat.getBtnRestore().y = arg2;
                }
            }
            return;
        }

        public function isStartMinimized():Boolean
        {
            return this.startMinimized;
        }

        public function getChatWidth():int
        {
            return this.chatWidth;
        }

        public function getChatHeight():int
        {
            return this.chatHeight;
        }

        public function isFastRegMode():Boolean
        {
            return this.fastRegMode;
        }

        public function showChat():void
        {
            this.chat.visible = true;
            return;
        }

        public function createClanRoom(arg1:String):void
        {
            if (!(arg1 == null) && arg1.length > 0) 
            {
                this.sendCommand(net.bigpoint.as3chat.Constants.CMD_CREATE_CLAN_ROOM, [arg1]);
            }
            return;
        }

        public function createGroupRoom(arg1:int, arg2:String, arg3:Boolean=true):void
        {
            this.autojoinGroup = arg3;
            var loc1:*;
            (loc1 = new Array()).push(arg1.toString());
            loc1.push(arg2);
            this.sendCommand(net.bigpoint.as3chat.Constants.CREATE_GROUP_ROOM, loc1);
            return;
        }

        public function createPrivateRoomWithUsers(arg1:String, arg2:String):void
        {
            var loc1:*=new Array();
            loc1.push(arg1);
            loc1.push(arg2);
            if (arg1.length == 0 || loc1.length == 0) 
            {
                this.chat.output(net.bigpoint.as3chat.Message.TYPE_HELP, this.languageHandler.getWord("globalchat.chat.system"), null, "Überprüfen Sie bitte Ihre Eingaben mit /help@" + this.languageHandler.getWord("globalchat.help.createpr"), this.getActiveRoomId());
            }
            else 
            {
                this.sendCommand(net.bigpoint.as3chat.Constants.CMD_CREATE_PRIVATE_ROOM_WITH_USERS, loc1);
            }
            return;
        }

        public function removeGroupRoom(arg1:int):void
        {
            var loc1:*=new Array();
            loc1.push(arg1.toString());
            this.sendCommand(net.bigpoint.as3chat.Constants.CMD_REMOVE_GROUP_ROOM, loc1);
            return;
        }

        public function leaveGroupRoom():void
        {
            var loc2:*=null;
            var loc3:*=null;
            var loc1:*=0;
            while (loc1 < this.roomList.length) 
            {
                loc2 = this.roomList[loc1];
                if (loc2.getRoomType() == net.bigpoint.as3chat.Room.GROUP_ROOM) 
                {
                    loc3 = new Array();
                    loc3.push(loc2.groupID.toString());
                    this.sendCommand(net.bigpoint.as3chat.Constants.CMD_LEAVE_GROUP_ROOM, loc3);
                }
                ++loc1;
            }
            return;
        }

        public function setTextListenerFunction(arg1:Function):void
        {
            this.textListener = arg1;
            return;
        }

        public function getChat():net.bigpoint.as3chat.Chat
        {
            return this.chat;
        }

        public static function decodeString(arg1:String):String
        {
            arg1 = arg1.replace(new RegExp("MSG_SEPERATOR", "g"), net.bigpoint.as3chat.Constants.MSG_SEPERATOR);
            arg1 = arg1.replace(new RegExp("PARAM_SEPERATOR", "g"), net.bigpoint.as3chat.Constants.PARAM_SEPERATOR);
            arg1 = arg1.replace(new RegExp("ATRIBUTE_SEPERATOR", "g"), net.bigpoint.as3chat.Constants.ATRIBUTE_SEPERATOR);
            arg1 = arg1.replace(new RegExp("OBJECT_SEPERATOR", "g"), net.bigpoint.as3chat.Constants.OBJECT_SEPERATOR);
            arg1 = arg1.replace(new RegExp("LINE_SEPERATOR", "g"), net.bigpoint.as3chat.Constants.LINE_SEPERATOR);
            return arg1;
        }

        public static function encodeString(arg1:String):String
        {
            arg1 = arg1.replace(new RegExp(net.bigpoint.as3chat.Constants.MSG_SEPERATOR, "g"), "MSG_SEPERATOR");
            arg1 = arg1.replace(new RegExp(net.bigpoint.as3chat.Constants.PARAM_SEPERATOR, "g"), "PARAM_SEPERATOR");
            arg1 = arg1.replace(new RegExp("\\|", "g"), "ATRIBUTE_SEPERATOR");
            arg1 = arg1.replace(new RegExp(net.bigpoint.as3chat.Constants.OBJECT_SEPERATOR, "g"), "OBJECT_SEPERATOR");
            arg1 = arg1.replace(new RegExp(net.bigpoint.as3chat.Constants.LINE_SEPERATOR, "g"), "LINE_SEPERATOR");
            return arg1;
        }

        public static function maskHTML(arg1:String):String
        {
            arg1 = arg1.replace(new RegExp("&", "g"), "&amp;");
            arg1 = arg1.replace(new RegExp("<", "g"), "&lt;");
            arg1 = arg1.replace(new RegExp(">", "g"), "&gt;");
            arg1 = arg1.replace(new RegExp("\"", "g"), "&quot;");
            arg1 = arg1.replace(new RegExp("\'", "g"), "&#039;");
            return arg1;
        }

        
        {
            DEBUG = false;
        }

        public function init(arg1:String, arg2:int, arg3:int, arg4:String, arg5:String, arg6:String, arg7:flash.geom.Point, arg8:flash.geom.Rectangle, arg9:Boolean, arg10:String=null, arg11:int=0, arg12:Boolean=false, arg13:int=0, arg14:int=0, arg15:Boolean=false, arg16:Boolean=false):void
        {
            this.username = mx.utils.StringUtil.trim(arg1);
            this.userId = arg2;
            this.projectId = arg3;
            this.language = arg4;
            this.clanName = this.trimClanName ? mx.utils.StringUtil.trim(arg5) : arg5;
            this.companyRoomIds = arg6.split(",");
            this.position = arg7;
            this._bounds = arg8;
            this.saveLastPosition = arg9;
            this.baseURL = arg10;
            this.configID = arg11;
            this.startMinimized = arg12;
            this.chatWidth = arg13;
            this.chatHeight = arg14;
            this.fastRegMode = arg15;
            this.showNewcomerChannel = arg16;
            this.setSettings();
            return;
        }

        public function initChatSecure(arg1:String, arg2:int, arg3:String, arg4:int, arg5:String, arg6:String, arg7:String, arg8:flash.geom.Point, arg9:flash.geom.Rectangle, arg10:Boolean, arg11:String=null, arg12:int=0, arg13:Boolean=false, arg14:int=0, arg15:int=0, arg16:Boolean=false, arg17:Boolean=false):void
        {
            this.username = mx.utils.StringUtil.trim(arg1);
            this.userId = arg2;
            this.sessionId = arg3;
            this.projectId = arg4;
            this.language = arg5;
            this.clanName = this.trimClanName ? mx.utils.StringUtil.trim(arg6) : arg6;
            this.companyRoomIds = arg7.split(",");
            this.position = arg8;
            this._bounds = arg9;
            this.saveLastPosition = arg10;
            this.baseURL = arg11;
            this.configID = arg12;
            this.startMinimized = arg13;
            this.chatWidth = arg14;
            this.chatHeight = arg15;
            this.fastRegMode = arg16;
            this.showNewcomerChannel = arg17;
            this.setSettings();
            return;
        }

        public function selectRoom(arg1:int):void
        {
            var loc1:*=this.chat.getTabBar().getTabs();
            var loc2:*=loc1[arg1];
            if (loc2 != null) 
            {
                this.chat.getTabBar().tabClick(loc2.getRoomId());
            }
            return;
        }

        public function setTabVisibility():void
        {
            return;
        }

        public function setTextSize(arg1:String, arg2:int):void
        {
            var loc1:*=this.css.getStyle(arg1);
            var loc2:*;
            (loc2 = new Object()).fontFamily = loc1.fontFamily;
            loc2.fontWeight = loc1.fontWeight;
            loc2.fontSize = arg2;
            loc2.color = loc1.color;
            this.css.setStyle(arg1, loc2);
            return;
        }

        public function setTextColor(arg1:String, arg2:String):void
        {
            var loc1:*=this.css.getStyle(arg1);
            var loc2:*;
            (loc2 = new Object()).fontFamily = loc1.fontFamily;
            loc2.fontWeight = loc1.fontWeight;
            loc2.fontSize = loc1.fontSize;
            loc2.color = "#" + arg2;
            this.css.setStyle(arg1, loc2);
            return;
        }

        internal function setSettings():void
        {
            this.sharedObject = flash.net.SharedObject.getLocal("globalchatAS3Client");
            if (this.saveLastPosition) 
            {
                if (this.sharedObject.data.xPosition != null) 
                {
                    this.position.x = this.sharedObject.data.xPosition;
                }
                if (this.sharedObject.data.xPosition != null) 
                {
                    this.position.y = this.sharedObject.data.yPosition;
                }
            }
            if (this.baseURL == null) 
            {
                if (this.baseURL == null) 
                {
                    this.baseURL = "";
                }
            }
            else if (!(this.baseURL.charAt((this.baseURL.length - 1)) == "/") && this.baseURL.length > 1) 
            {
                this.baseURL = this.baseURL + "/";
            }
            var loc1:*=this.baseURL + "cfg/base_" + this.projectId + ".xml";
            this.date = new Date();
            this.settingsHandler = net.bigpoint.flashcorelib.resources.SettingsHandler.getInstance();
            this.settingsHandler.addEventListener(net.bigpoint.flashcorelib.resources.SettingsHandlerEvent.SETTINGSLOADED, this.onBaseSettingsLoaded);
            this.settingsHandler.loadSettings(loc1);
            return;
        }

        public function hasFocus():Boolean
        {
            if (this.chat.getTextInput().focusEnabled && !(this.chat.getTextInput().focusManager.getFocus() == null)) 
            {
                return true;
            }
            return false;
        }

        public function removeFocus():void
        {
            this.chat.getTextInput().focusEnabled = false;
            this.chat.getTextInput().focusManager.setFocus(null);
            return;
        }

        public function updatePosition(arg1:int, arg2:int):void
        {
            this.chat.x = arg1;
            this.chat.y = arg2;
            return;
        }

        public function isDraggable():Boolean
        {
            return this.chat.isDraggable();
        }

        public function setDraggable(arg1:Boolean):void
        {
            this.chat.setDraggable(arg1);
            return;
        }

        public function showMessage(arg1:String):void
        {
            this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, arg1, this.activeRoomId);
            return;
        }

        internal function onBaseSettingsLoaded(arg1:net.bigpoint.flashcorelib.resources.SettingsHandlerEvent):void
        {
            this.settingsHandler.removeEventListener(net.bigpoint.flashcorelib.resources.SettingsHandlerEvent.SETTINGSLOADED, this.onBaseSettingsLoaded);
            this.settingsHandler.addEventListener(net.bigpoint.flashcorelib.resources.SettingsHandlerEvent.SETTINGSLOADED, this.onExtendedSettingsLoaded);
            var loc1:*=this.baseURL + "cfg/extended_" + this.projectId + "_" + this.configID + ".xml";
            this.settingsHandler.loadSettings(loc1);
            return;
        }

        internal function onExtendedSettingsLoaded(arg1:net.bigpoint.flashcorelib.resources.SettingsHandlerEvent):void
        {
            this.settingsHandler.removeEventListener(net.bigpoint.flashcorelib.resources.SettingsHandlerEvent.SETTINGSLOADED, this.onExtendedSettingsLoaded);
            this.languageHandler = net.bigpoint.flashcorelib.resources.LanguageHandler.getInstance();
            var loc1:*=this.baseURL + "lang/" + this.language + "/resource.xml";
            this.languageHandler.loadLanguage(loc1);
            this.languageHandler.addEventListener(net.bigpoint.flashcorelib.resources.LanguageHandlerEvent.LANGUAGELOADED, this.onLanguageLoaded);
            return;
        }

        internal function onLanguageLoaded(arg1:net.bigpoint.flashcorelib.resources.LanguageHandlerEvent):void
        {
            this.languageHandler.removeEventListener(net.bigpoint.flashcorelib.resources.LanguageHandlerEvent.LANGUAGELOADED, this.onLanguageLoaded);
            var loc1:*=new flash.net.URLLoader();
            var loc2:*=this.baseURL + "skin/" + this.settingsHandler.getVariable("chatGFX") + "/styles.css";
            var loc3:*=new flash.net.URLRequest(loc2);
            loc1.addEventListener(flash.events.Event.COMPLETE, this.onCSSLoaded);
            loc1.load(loc3);
            return;
        }

        internal function onCSSLoaded(arg1:flash.events.Event):void
        {
            var loc1:*=arg1.target as flash.net.URLLoader;
            loc1.removeEventListener(flash.events.Event.COMPLETE, this.onCSSLoaded);
            this.css = new flash.text.StyleSheet();
            this.css.parseCSS(flash.net.URLLoader(arg1.target).data);
            dispatchEvent(new flash.events.Event("ChatEvent.CSS_LOADED"));
            this.mediaHandler = net.bigpoint.flashcorelib.resources.MediaHandler.getInstance();
            this.mediaHandler.addEventListener(net.bigpoint.flashcorelib.resources.MediaHandlerEvent.ALLMEDIALOADED, this.onMediaLoaded);
            var loc2:*=["btn_maximize_normal", "btn_maximize_hover", "btn_maximize_pressed", "upLeft", "upMid", "upRight", "midLeft", "midMid", "midRight", "downLeft", "downMid", "downRight", "tab", "holder", "btn_right_normal", "btn_left_normal", "btn_left_over", "btn_right_over", "btn_minimize_normal", "btn_minimize_over", "btn_restore_normal", "btn_restore_over", "resize_arrow"];
            var loc3:*=["btn_maximize_normal.png", "btn_maximize_hover.png", "btn_maximize_pressed.png", "upLeft.png", "upMid.png", "upRight.png", "midLeft.png", "midMid.png", "midRight.png", "downLeft.png", "downMid.png", "downRight.png", "tab.png", "holder.png", "btn_right_normal.png", "btn_left_normal.png", "btn_left_over.png", "btn_right_over.png", "btn_minimize_normal.png", "btn_minimize_over.png", "btn_restore_normal.png", "btn_restore_over.png", "resize_arrow.png"];
            if (this.parseBoolean(this.settingsHandler.getVariable("useScrollBarBg") as String)) 
            {
                loc2.push("scrollBarUp", "scrollBarDown", "scrollBarBack");
                loc3.push("scrollBarUp.png", "scrollBarDown.png", "scrollBarBack.png");
            }
            this.mediaHandler.addToQueue(net.bigpoint.flashcorelib.resources.MediaHandler.TYPE_BITMAP, loc2, loc3, this.baseURL + "skin/" + this.settingsHandler.getVariable("chatGFX"));
            this.mediaHandler.loadMedia();
            return;
        }

        internal function onMediaLoaded(arg1:net.bigpoint.flashcorelib.resources.MediaHandlerEvent):void
        {
            this.mediaHandler.removeEventListener(net.bigpoint.flashcorelib.resources.MediaHandlerEvent.ALLMEDIALOADED, this.onMediaLoaded);
            this.socket = new flash.net.Socket();
            this.socket.addEventListener(flash.events.Event.CONNECT, this.onConnection);
            this.socket.addEventListener(flash.events.IOErrorEvent.IO_ERROR, this.onIOError);
            this.socket.addEventListener(flash.events.SecurityErrorEvent.SECURITY_ERROR, this.onSecurityError);
            this.socket.addEventListener(flash.events.ProgressEvent.SOCKET_DATA, this.onData);
            this.socket.addEventListener(flash.events.Event.CLOSE, this.onConnectionLost);
            this.chat = new net.bigpoint.as3chat.Chat(this, net.bigpoint.as3chat.Chat.MODE_DEFAULT);
            if (this.size != null) 
            {
                this.chat.setSize(this.size.x, this.size.y);
            }
            this.addChild(this.chat);
            if (this.chat.isResizerVisible()) 
            {
                this.addChild(this.chat.getResizeArrow());
            }
            if (this.startMinimized) 
            {
                this.chat.visible = false;
                if (this.chat.isBtnRestoreVisible()) 
                {
                    this.addChild(this.chat.getBtnRestore());
                }
            }
            this.chat.x = this.position.x;
            this.chat.y = this.position.y;
            dispatchEvent(new flash.events.Event("ChatEvent.ALL_LOADED"));
            this.connect();
            return;
        }

        public function onSecurityError(arg1:flash.events.SecurityErrorEvent):void
        {
            this.showConnectionLostMessage();
            return;
        }

        public function cleanup():void
        {
            if (this.socket != null) 
            {
                this.socket.removeEventListener(flash.events.Event.CONNECT, this.onConnection);
                this.socket.removeEventListener(flash.events.IOErrorEvent.IO_ERROR, this.onIOError);
                this.socket.removeEventListener(flash.events.SecurityErrorEvent.SECURITY_ERROR, this.onSecurityError);
                this.socket.removeEventListener(flash.events.ProgressEvent.SOCKET_DATA, this.onData);
                this.socket.removeEventListener(flash.events.Event.CLOSE, this.onConnectionLost);
                if (this.socket.connected) 
                {
                    this.socket.close();
                }
            }
            if (this.timer != null) 
            {
                this.timer.stop();
                this.timer.removeEventListener("timer", this.onTimerComplete);
            }
            this.chat.cleanup();
            return;
        }

        internal function connect():void
        {
            var loc1:*=String(this.settingsHandler.getVariable("host"));
            var loc2:*=int(this.settingsHandler.getVariable("port"));
            this.socket.connect(loc1, loc2);
            return;
        }

        internal function onConnectionLost(arg1:flash.events.Event):void
        {
            this.showConnectionLostMessage();
            return;
        }

        internal function showConnectionLostMessage():void
        {
            if (this.userBlocked) 
            {
                return;
            }
            if (this.activeRoomId == -1) 
            {
                this.createTmpRoom();
                this.activeRoomId = 100000000;
            }
            this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.connectionLost") + " chatVersion:" + this.chatVersion, this.activeRoomId);
            this.tryToConnect();
            return;
        }

        internal function createTmpRoom():void
        {
            if (this.tmpRoom == null) 
            {
                this.tmpRoom = new net.bigpoint.as3chat.Room(100000000, "tmp", 0, -1, net.bigpoint.as3chat.Room.NORMAL_ROOM, false);
                this.roomList.push(this.tmpRoom);
            }
            return;
        }

        internal function onData(arg1:flash.events.ProgressEvent):void
        {
            var loc6:*=0;
            var loc1:*=this.socket.bytesAvailable;
            var loc2:*="";
            var loc3:*=new flash.utils.ByteArray();
            var loc4:*=0;
            while (loc4 < loc1) 
            {
                if ((loc6 = this.socket.readByte()) != 0) 
                {
                    loc3.writeByte(loc6);
                }
                ++loc4;
            }
            loc3.position = 0;
            loc2 = loc3.readUTFBytes(loc3.bytesAvailable);
            if (loc2.charAt((loc2.length - 1)) != "#") 
            {
                this.buffer = this.buffer + loc2;
                return;
            }
            else 
            {
                loc2 = this.buffer + loc2;
                this.buffer = "";
            }
            loc2 = loc2.substr(0, (loc2.length - 1));
            var loc5:*=loc2.split(net.bigpoint.as3chat.Constants.LINE_SEPERATOR);
            loc4 = 0;
            while (loc4 < loc5.length) 
            {
                this.processMessage(loc5[loc4]);
                ++loc4;
            }
            return;
        }

        internal function processMessage(arg1:String):void
        {
            var loc3:*=null;
            var loc4:*=null;
            var loc5:*=null;
            var loc6:*=0;
            var loc7:*=0;
            var loc8:*=null;
            var loc9:*=0;
            var loc10:*=null;
            var loc11:*=null;
            var loc12:*=0;
            var loc13:*=null;
            var loc14:*=null;
            var loc15:*=false;
            var loc16:*=null;
            var loc17:*=0;
            var loc18:*=null;
            var loc19:*=0;
            var loc20:*=null;
            var loc21:*=null;
            var loc22:*=0;
            var loc23:*=null;
            var loc24:*=null;
            var loc25:*=null;
            var loc26:*=null;
            var loc27:*=null;
            var loc28:*=NaN;
            var loc29:*=null;
            var loc30:*=0;
            var loc31:*=0;
            var loc32:*=null;
            var loc33:*=null;
            var loc34:*=NaN;
            var loc35:*=NaN;
            var loc36:*=NaN;
            var loc37:*=NaN;
            var loc38:*=null;
            var loc39:*=null;
            var loc40:*=null;
            var loc41:*=0;
            var loc42:*=null;
            var loc43:*=null;
            var loc44:*=0;
            var loc45:*=0;
            var loc46:*=0;
            var loc47:*=false;
            var loc48:*=null;
            var loc49:*=0;
            var loc50:*=null;
            var loc51:*=null;
            var loc52:*=0;
            var loc53:*=null;
            var loc54:*=undefined;
            var loc55:*=0;
            var loc56:*=null;
            var loc57:*=0;
            var loc58:*=0;
            var loc59:*=null;
            var loc60:*=null;
            var loc61:*=null;
            var loc62:*=null;
            var loc63:*=null;
            var loc64:*=null;
            var loc65:*=null;
            var loc1:*=arg1.split(net.bigpoint.as3chat.Constants.MSG_SEPERATOR);
            var loc2:*=loc1[0];
            if (loc1.length > 1) 
            {
                loc3 = String(loc1[1]).split(net.bigpoint.as3chat.Constants.PARAM_SEPERATOR);
            }
            var loc66:*=loc2;
            switch (loc66) 
            {
                case net.bigpoint.as3chat.Constants.CMD_USER_LOGIN_OK:
                {
                    this.userId = loc3[0];
                    this.sendCommand(net.bigpoint.as3chat.Constants.CMD_GET_USER_ROOMLIST, null);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_USER_LOGIN_NOK:
                {
                    this.createTmpRoom();
                    this.activeRoomId = 100000000;
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, "user already exist", this.activeRoomId);
                    this.userBlocked = true;
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_PING:
                {
                    this.sendCommand(net.bigpoint.as3chat.Constants.CMD_PONG);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_USER_LOGIN_NOK:
                {
                    this.userId = loc3[0];
                    this.sendCommand(net.bigpoint.as3chat.Constants.CMD_GET_USER_ROOMLIST, null);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_SET_USER_ROOMLIST:
                {
                    loc5 = (loc4 = loc3[0]).split(net.bigpoint.as3chat.Constants.OBJECT_SEPERATOR);
                    loc41 = 0;
                    while (loc41 < loc5.length) 
                    {
                        loc43 = (loc42 = loc5[loc41]).split(net.bigpoint.as3chat.Constants.ATRIBUTE_SEPERATOR);
                        loc9 = parseInt(loc43[0]);
                        loc18 = decodeString(loc43[1]);
                        loc44 = parseInt(loc43[2]);
                        loc45 = parseInt(loc43[3]);
                        loc46 = parseInt(loc43[4]);
                        loc47 = this.parseBooleanFromInt(parseInt(loc43[5]));
                        if (loc46 != net.bigpoint.as3chat.Room.SCALABLE_ROOM_PARENT) 
                        {
                            loc48 = new net.bigpoint.as3chat.Room(loc9, loc18, loc44, loc45, loc46, loc47);
                        }
                        else 
                        {
                            loc48 = new net.bigpoint.as3chat.ScalableRoom(loc9, loc18, loc44, loc45, loc46, loc47);
                            this.scalableRooms = true;
                        }
                        this.roomList.push(loc48);
                        ++loc41;
                    }
                    this.chat.updateTabBar();
                    dispatchEvent(new flash.events.Event("ChatEvent.CONNECTED"));
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_SCAL_JOINED_CHILD:
                {
                    loc6 = loc3[0];
                    loc7 = loc3[1];
                    loc8 = loc3[2];
                    this.setActiveThemeId(loc6);
                    this.setActiveRoomId(loc7);
                    this.setActiveScalRoomId(loc7);
                    loc49 = 0;
                    while (loc49 < this.roomList.length) 
                    {
                        if ((loc48 = this.roomList[loc49]).getRoomId() == loc6) 
                        {
                            (loc50 = net.bigpoint.as3chat.ScalableRoom(loc48)).setChildRoomId(loc7);
                            loc50.setChildRoomName(loc8);
                            loc50.setThemeId(loc6);
                            loc50.setRoomFirstRun(false);
                            loc16 = (loc16 = this.languageHandler.getWord("globalchat.chat.joinRoom")).replace("%ROOM", loc50.getRoomName());
                            loc51 = new net.bigpoint.as3chat.Message(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc16);
                            loc50.addMessage(loc51);
                            this.chat.addToJoined(loc7);
                            this.chat.getTextOutput().htmlText = loc50.getAllMessages();
                            this.chat.getTextOutput().scrollV = this.chat.getTextOutput().maxScrollV;
                        }
                        ++loc49;
                    }
                    this.chat.updateTabBar();
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_ADMIN_MSG:
                {
                    loc9 = loc3[0];
                    loc10 = loc3[1];
                    loc11 = decodeString(loc3[2]);
                    loc12 = parseInt(loc3[3]);
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_MODERATOR, loc10, null, loc11, loc9, loc12);
                    (loc13 = new net.bigpoint.as3chat.ChatEvent("MessageEvent.TEXT_RECEIVED")).username = loc10;
                    loc13.message = loc11;
                    loc13.clanTag = loc14;
                    loc13.usertype = loc12;
                    dispatchEvent(loc13);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_USER_MSG:
                {
                    loc9 = loc3[0];
                    loc10 = decodeString(loc3[1]);
                    loc11 = decodeString(loc3[2]);
                    loc14 = null;
                    if (loc3.length > 3) 
                    {
                        loc14 = decodeString(loc3[3]);
                    }
                    if (this.userIsIgnored(loc10)) 
                    {
                        return;
                    }
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_USER, loc10, loc14, loc11, loc9);
                    (loc13 = new net.bigpoint.as3chat.ChatEvent("MessageEvent.TEXT_RECEIVED")).username = loc10;
                    loc13.message = loc11;
                    loc13.clanTag = loc14;
                    loc13.usertype = -1;
                    dispatchEvent(loc13);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_SCHEDULED_MSG:
                {
                    loc15 = this.parseBooleanFromInt(parseInt(loc3[0]));
                    loc11 = decodeString(loc3[1]);
                    loc41 = 0;
                    while (loc41 < this.roomList.length) 
                    {
                        loc48 = this.roomList[loc41];
                        if (loc15 && loc48.newcomerRoom) 
                        {
                            this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc11, loc48.getRoomId());
                        }
                        else if (!loc15) 
                        {
                            this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc11, loc48.getRoomId());
                        }
                        ++loc41;
                    }
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_KICK_USER:
                {
                    loc16 = (loc16 = this.languageHandler.getWord("globalchat.chat.kickmsg")).replace("%ADMIN", this.languageHandler.getWord("globalchat.chat.administrator"));
                    if (loc3[0].length > 0) 
                    {
                        loc16 = loc16 + " " + decodeString(loc3[0]);
                    }
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc16, this.activeRoomId);
                    this.userBlocked = true;
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_KICK_BY_SYSTEM:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.kickBySystem"), this.activeRoomId);
                    this.userBlocked = true;
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_BANN_USER:
                {
                    loc16 = (loc16 = this.languageHandler.getWord("globalchat.chat.banmsg")).replace("%ADMIN", this.languageHandler.getWord("globalchat.chat.administrator"));
                    if (loc3[0].length > 0) 
                    {
                        loc16 = loc16 + " " + decodeString(loc3[0]);
                    }
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc16, this.activeRoomId);
                    this.userBlocked = true;
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_BANN_BY_SYSTEM:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.banBySystem"), this.activeRoomId);
                    this.userBlocked = true;
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_PRIVATE_ROOM_CREATED:
                {
                    loc17 = loc3[0];
                    loc18 = decodeString(loc3[1]);
                    loc19 = loc3[2];
                    loc20 = decodeString(loc3[3]);
                    (loc21 = new net.bigpoint.as3chat.Room(loc17, loc18, 99, -1, net.bigpoint.as3chat.Room.PRIVATE_ROOM, false)).setCreatorId(loc19);
                    loc21.setCreatorName(loc20);
                    this.roomList.push(loc21);
                    if (this.userId != loc21.getCreatorId()) 
                    {
                        loc16 = (loc16 = (loc16 = this.languageHandler.getWord("globalchat.chat.inviteMessage")).replace("%CREATOR", loc20)).replace("%ROOM", loc18);
                        this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc16, this.activeRoomId);
                        this.chat.output(net.bigpoint.as3chat.Message.TYPE_INVITE, "", null, this.languageHandler.getWord("globalchat.chat.inviteMessageAccept") + ";" + loc17, this.activeRoomId);
                    }
                    else 
                    {
                        this.chat.updateTabBar();
                        this.chat.selectTabIndexByRoomId(loc21.getRoomId());
                        (loc3 = new Array()).push(loc21.getRoomId());
                        this.sendCommand(net.bigpoint.as3chat.Constants.CMD_USER_JOIN, loc3);
                    }
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_USER_ADDED_TO_DYN_ROOM:
                {
                    loc22 = loc3[0];
                    loc23 = loc3[1];
                    loc47 = this.parseBooleanFromInt(parseInt(loc43[2]));
                    loc24 = new net.bigpoint.as3chat.Room(loc22, loc23, 99, -1, net.bigpoint.as3chat.Room.DYNAMIC_ROOM, loc47);
                    this.roomList.push(loc24);
                    this.chat.updateTabBar();
                    this.chat.selectTabIndexByRoomId(loc24.getRoomId());
                    (loc3 = new Array()).push(loc24.getRoomId());
                    loc3.push(this.userId);
                    this.sendCommand(net.bigpoint.as3chat.Constants.CMD_USER_JOINED_DYN_ROOM, loc3);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_USER_JOINED_DYN_ROOM:
                {
                    loc22 = loc3[0];
                    loc25 = "";
                    loc52 = 0;
                    while (loc52 < this.roomList.length) 
                    {
                        if ((loc53 = this.roomList[loc52]).getRoomId() == loc22) 
                        {
                            loc25 = loc53.getRoomName();
                        }
                        ++loc52;
                    }
                    loc26 = loc3[1];
                    loc16 = (loc16 = (loc16 = this.languageHandler.getWord("globalchat.chat.userEnterRoom")).replace("%USER", loc26)).replace("%ROOM", loc25);
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc16, this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_USER_REMOVED_FROM_DYN_ROOM:
                {
                    loc22 = loc3[0];
                    if (this.chat.getTabBar().getSelectedRoomId() == loc22) 
                    {
                        this.setActiveNormalRoomId(this.getActiveNormalRoomId());
                        this.chat.selectTabIndexByRoomId(this.getActiveNormalRoomId());
                    }
                    this.deleteRoom(loc22);
                    this.chat.updateTabBar();
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_USER_LEFT_DYN_ROOM:
                {
                    loc22 = loc3[0];
                    loc26 = loc3[1];
                    loc25 = "";
                    loc66 = 0;
                    var loc67:*=this.roomList;
                    for (loc54 in loc67) 
                    {
                        if ((loc24 = net.bigpoint.as3chat.Room(this.roomList[loc54])).getRoomId() != loc22) 
                        {
                            continue;
                        }
                        loc25 = loc24.getRoomName();
                        break;
                    }
                    loc16 = (loc16 = (loc16 = this.languageHandler.getWord("globalchat.chat.userLeaveRoom")).replace("%USER", loc26)).replace("%ROOM", loc25);
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc16, this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_SECTOR_ROOM_UPDATE:
                {
                    loc27 = String(loc3[0]).split(net.bigpoint.as3chat.Constants.OBJECT_SEPERATOR);
                    loc55 = 0;
                    while (loc55 < (loc27.length - 1)) 
                    {
                        if ((loc57 = (loc56 = String(loc27[loc55]).split(net.bigpoint.as3chat.Constants.ATRIBUTE_SEPERATOR))[0]) != -1) 
                        {
                            this.deleteRoom(loc57);
                            this.chat.updateTabBar();
                        }
                        loc58 = loc56[1];
                        loc59 = loc56[2];
                        (loc60 = new net.bigpoint.as3chat.Room(loc58, loc59, 99, -1, net.bigpoint.as3chat.Room.SECTOR_ROOM, this.parseBooleanFromInt(parseInt(loc56[4])))).setSectorId(loc56[3]);
                        this.roomList.push(loc60);
                        this.chat.updateTabBar();
                        this.chat.selectTabIndexByRoomId(loc60.getRoomId());
                        ++loc55;
                    }
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_USER_JOIN_INVITED_ROOM:
                {
                    loc16 = (loc16 = (loc16 = this.languageHandler.getWord("globalchat.chat.userEnterRoom")).replace("%USER", decodeString(loc3[0]))).replace("%ROOM", "");
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc16, this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_USER_LEFT_INVITED_ROOM:
                {
                    loc28 = loc3[0];
                    loc29 = decodeString(loc3[1]);
                    loc9 = loc3[2];
                    if (loc28 != this.userId) 
                    {
                        loc16 = (loc16 = (loc16 = this.languageHandler.getWord("globalchat.chat.userLeaveRoom")).replace("%USER", loc29)).replace("%ROOM", "");
                        this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc16, this.activeRoomId);
                    }
                    else 
                    {
                        if (this.chat.getTabBar().getSelectedRoomId() == loc9) 
                        {
                            if (this.scalableRooms) 
                            {
                                this.setActiveRoomId(this.getActiveScalRoomId());
                                this.chat.selectTabIndexByRoomId(this.getActiveRoomId());
                            }
                            else 
                            {
                                this.setActiveNormalRoomId(this.getActiveNormalRoomId());
                                this.chat.selectTabIndexByRoomId(this.getActiveNormalRoomId());
                            }
                        }
                        this.deleteRoom(loc9);
                        this.chat.updateTabBar();
                    }
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_GROUP_ROOM_CREATED:
                {
                    loc30 = loc3[0];
                    loc31 = loc3[1];
                    loc18 = decodeString(loc3[2]);
                    loc47 = this.parseBooleanFromInt(parseInt(loc3[3]));
                    (loc32 = new net.bigpoint.as3chat.Room(loc30, loc18, 99, -1, net.bigpoint.as3chat.Room.GROUP_ROOM, loc47)).groupID = loc31;
                    this.roomList.push(loc32);
                    this.chat.updateTabBar();
                    if (this.autojoinGroup) 
                    {
                        this.chat.selectTabIndexByRoomId(loc30);
                        (loc3 = new Array()).push(loc30);
                        this.sendCommand(net.bigpoint.as3chat.Constants.CMD_USER_JOIN, loc3);
                    }
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_NO_MORE_PRIVATE_ROOMS_ALLOWED:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.noMorePrivateRoomsAllowed"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_CREATE_ROOM_WRONG_ARGUMENTS:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.wrongArguments"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_ROOMNAME_TOO_SHORT:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.roomnameToShort"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_PRIVATE_ROOM_EXIST:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.privateRoomAlreadyExist"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_ROOM_NAME_NOT_ALLOWED:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.roomNameNotAllowed"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_ROOM_DELETED:
                {
                    loc33 = this.getRoom(loc3[0]);
                    if (this.chat.getTabBar().getSelectedRoomId() == loc33.getRoomId()) 
                    {
                        if (this.scalableRooms) 
                        {
                            this.setActiveRoomId(this.getActiveScalRoomId());
                        }
                        else 
                        {
                            this.setActiveRoomId(this.getActiveNormalRoomId());
                            this.chat.selectTabIndexByRoomId(this.getActiveNormalRoomId());
                        }
                    }
                    if (loc33.getRoomType() == net.bigpoint.as3chat.Room.PRIVATE_ROOM) 
                    {
                        if (loc33.getCreatorId() == this.userId || loc33.isInvited()) 
                        {
                            loc16 = (loc16 = this.languageHandler.getWord("globalchat.chat.privateRoomDeleted")).replace("%ROOM", loc33.getRoomName());
                            this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc16, this.activeRoomId);
                        }
                    }
                    this.deleteRoom(loc3[0]);
                    this.chat.updateTabBar();
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_PRIVATE_ROOM_NOT_EXIST:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.PrivateRoomNotExist"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_WRONG_COMMAND:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.wrongCommand"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_CANNOT_INVITE_YOURSELF:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.cannotInviteYourself"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_INVITE_ERROR_NOT_YOUR_ROOM:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.inviteErrorNotYourRoom"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_YOU_INVITED:
                {
                    loc16 = (loc16 = this.languageHandler.getWord("globalchat.chat.youInvited")).replace("%USER", loc3[0]);
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc16, this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_NO_WHISPER_MESSAGE:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.noWhisperMessage"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_USER_NOT_EXIST:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.userNotExistOrOnline"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_USER_WHISPERS:
                {
                    loc10 = decodeString(loc3[0]);
                    if (this.userIsIgnored(loc10)) 
                    {
                        return;
                    }
                    loc16 = (loc16 = this.languageHandler.getWord("globalchat.chat.userWhispers")).replace("%USER", loc10);
                    loc16 = loc10 + "WHISPER_SEPERATOR" + loc16;
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_WHISPER, loc16, null, decodeString(loc3[1]), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_YOU_WHISPER:
                {
                    loc16 = (loc16 = this.languageHandler.getWord("globalchat.chat.youWhisper")).replace("%USER", decodeString(loc3[0]));
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_YOU_WHISPER, loc16, null, decodeString(loc3[1]), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_WRONG_CHAT_VERSION:
                {
                    loc48 = new net.bigpoint.as3chat.Room(-1, "tmp", 0, -1, net.bigpoint.as3chat.Room.NORMAL_ROOM, false);
                    this.roomList.push(loc48);
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.wrongVersion"), this.activeRoomId);
                    this.socket.close();
                    this.userBlocked = true;
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_FLOOD_WARNING:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.floodWarning"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_INVITE_ERROR_USER_NOT_FOUND:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.inviteErrorUserNotFound"), this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_IP_FAILED:
                {
                    this.createTmpRoom();
                    this.activeRoomId = 100000000;
                    this.userBlocked = true;
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.loginerror.bannedForever") + " code 0", this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_AUTH_FAILED:
                {
                    this.createTmpRoom();
                    this.activeRoomId = 100000000;
                    this.userBlocked = true;
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, "Authorization failed!", this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_SHOW_BANN_MESSAGE:
                {
                    this.createTmpRoom();
                    this.activeRoomId = 100000000;
                    this.userBlocked = true;
                    if (loc3[5] == 1) 
                    {
                        this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.loginerror.bannedForever"), this.activeRoomId);
                        return;
                    }
                    loc34 = parseInt(loc3[4]);
                    loc35 = Math.floor(loc34 / 60);
                    if ((loc36 = Math.floor(loc35 / 24)) > 0) 
                    {
                        loc37 = loc36;
                        loc38 = this.languageHandler.getWord("globalchat.loginerror.days");
                    }
                    else if (loc35 > 0) 
                    {
                        loc37 = loc35;
                        loc38 = this.languageHandler.getWord("globalchat.loginerror.hours");
                    }
                    else if (loc34 > 0) 
                    {
                        loc37 = loc34;
                        loc38 = this.languageHandler.getWord("globalchat.loginerror.minutes");
                    }
                    loc16 = (loc16 = (loc16 = (loc16 = (loc16 = (loc16 = (loc16 = this.languageHandler.getWord("globalchat.loginerror.banned")).replace("%BEGINDATE", loc3[0])).replace("%ENDDATE", loc3[1])).replace("%BEGINTIME", loc3[2])).replace("%ENDTIME", loc3[3])).replace("%PERIOD", loc37)).replace("%TIME", loc38);
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc16, this.activeRoomId);
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_SHOW_USERS:
                {
                    loc39 = loc3[0];
                    loc40 = "";
                    if (loc39 != "-") 
                    {
                        loc62 = (loc61 = loc39).split(net.bigpoint.as3chat.Constants.OBJECT_SEPERATOR);
                        loc41 = 0;
                        while (loc41 < loc62.length) 
                        {
                            loc64 = (loc63 = loc62[loc41]).split(net.bigpoint.as3chat.Constants.ATRIBUTE_SEPERATOR);
                            loc10 = decodeString(loc64[0]);
                            loc65 = decodeString(loc64[1]);
                            if (loc64[1] != "noclan") 
                            {
                                loc40 = loc40 + ("[" + loc65 + "]" + loc10 + ", ");
                            }
                            else 
                            {
                                loc40 = loc40 + (loc10 + ", ");
                            }
                            ++loc41;
                        }
                        this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.userInRoom") + "\n" + loc40.substring(0, loc40.length - 2), this.activeRoomId);
                    }
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_SHOW_MODERATORS:
                {
                    loc39 = loc3[0];
                    loc40 = "";
                    if (loc39 != "-") 
                    {
                        loc62 = (loc61 = loc39).split(net.bigpoint.as3chat.Constants.OBJECT_SEPERATOR);
                        loc41 = 0;
                        while (loc41 < loc62.length) 
                        {
                            loc40 = loc40 + (loc62[loc41] + ", ");
                            ++loc41;
                        }
                        this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.moderatorsInRoom") + "\n" + loc40.substring(0, loc40.length - 2), this.activeRoomId);
                    }
                    break;
                }
                case net.bigpoint.as3chat.Constants.CMD_DEVELOPER_MSG:
                {
                    this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc3[0], this.activeRoomId);
                    break;
                }
            }
            return;
        }

        public function parseBoolean(arg1:String):Boolean
        {
            if (arg1 == "true") 
            {
                return true;
            }
            return false;
        }

        public function parseBooleanFromInt(arg1:int):Boolean
        {
            if (arg1 == 1) 
            {
                return true;
            }
            return false;
        }

        public function sendCommand(arg1:String, arg2:Array=null):void
        {
            var loc2:*=0;
            if (this.userBlocked) 
            {
                return;
            }
            var loc1:*=arg1 + net.bigpoint.as3chat.Constants.MSG_SEPERATOR + this.activeRoomId;
            if (arg2 != null) 
            {
                loc1 = loc1 + net.bigpoint.as3chat.Constants.MSG_SEPERATOR;
                loc2 = 0;
                while (loc2 < arg2.length) 
                {
                    loc1 = loc1 + arg2[loc2] + net.bigpoint.as3chat.Constants.PARAM_SEPERATOR;
                    ++loc2;
                }
            }
            loc1 = loc1 + "\n";
            this.socket.writeUTFBytes(loc1);
            this.socket.flush();
            return;
        }

        internal function onConnection(arg1:flash.events.Event):void
        {
            this.roomList.length = 0;
            this.chat.resetChat();
            this.firstRun = true;
            this.username = encodeString(this.username);
            this.clanName = encodeString(this.clanName);
            var loc1:*=[];
            loc1[0] = this.username;
            loc1[1] = this.userId;
            loc1[2] = this.sessionId;
            loc1[3] = this.projectId;
            loc1[4] = this.language;
            loc1[5] = this.clanName;
            loc1[6] = this.chatVersion;
            loc1[7] = this.convertBooleanToInt(this.showNewcomerChannel);
            loc1[8] = -1;
            this.sendCommand(net.bigpoint.as3chat.Constants.CMD_USER_LOGIN, loc1);
            return;
        }

        internal function convertBooleanToInt(arg1:Boolean):int
        {
            if (arg1) 
            {
                return 1;
            }
            return 0;
        }

        internal function randomNumber(arg1:int, arg2:int):Number
        {
            return Math.round(Math.random() * (arg2 - arg1)) + arg1;
        }

        internal function tryToConnect():void
        {
            var loc1:*=Math.round(Math.random() * 45);
            var loc2:*=this.languageHandler.getWord("globalchat.chat.reconnect");
            loc2 = loc2.replace("%SECONDS", loc1);
            this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, loc2, this.activeRoomId);
            this.timer = new flash.utils.Timer(loc1 * 1000, 0);
            this.timer.addEventListener("timer", this.onTimerComplete);
            this.timer.start();
            return;
        }

        internal function onTimerComplete(arg1:flash.events.TimerEvent):void
        {
            var loc1:*=arg1.target as flash.utils.Timer;
            loc1.stop();
            loc1.removeEventListener("timer", this.onTimerComplete);
            this.chat.output(net.bigpoint.as3chat.Message.TYPE_SYSTEM, this.languageHandler.getWord("globalchat.chat.system"), null, this.languageHandler.getWord("globalchat.chat.connecting"), this.activeRoomId);
            this.connect();
            return;
        }

        public function getUsername():String
        {
            return this.username;
        }

        public function setReconnectTime(arg1:int):void
        {
            this.reconnectTime = arg1;
            return;
        }

        public function getLanguage():String
        {
            return this.language;
        }

        public function getProjectId():int
        {
            return this.projectId;
        }

        public function ignoreUser(arg1:String):void
        {
            this.ignoredUsers.push(arg1.toLowerCase());
            return;
        }

        internal var settingsHandler:net.bigpoint.flashcorelib.resources.SettingsHandler;

        internal var mediaHandler:net.bigpoint.flashcorelib.resources.MediaHandler;

        internal var languageHandler:net.bigpoint.flashcorelib.resources.LanguageHandler;

        internal var date:Date;

        internal var roomList:Array;

        public var scalableRooms:Boolean=false;

        public var css:flash.text.StyleSheet;

        internal var chat:net.bigpoint.as3chat.Chat;

        public var firstRun:Boolean=true;

        public var firstRoomId:int;

        internal var startMinimized:Boolean;

        internal var reconnectTime:int=5;

        internal var baseURL:String;

        internal var buffer:String="";

        internal var username:String;

        internal var sessionId:String="-1";

        internal var projectId:int;

        internal var language:String;

        internal var clanName:String;

        internal var companyRoomIds:Array;

        internal var socket:flash.net.Socket;

        internal var activeThemeId:int=-1;

        internal var activeRoomId:int=-1;

        internal var activeScalRoomId:int=-1;

        internal var activeNormalRoomId:int=-1;

        internal var ignoredUsers:Array;

        internal var chatVersion:String="2.3.3";

        internal var userBlocked:Boolean;

        internal var position:flash.geom.Point;

        internal var _bounds:flash.geom.Rectangle;

        internal var saveLastPosition:Boolean;

        internal var sharedObject:flash.net.SharedObject;

        internal var tmpRoom:net.bigpoint.as3chat.Room;

        internal var configID:int;

        internal var timer:flash.utils.Timer;

        internal var chatWidth:int;

        internal var chatHeight:int;

        internal var fastRegMode:Boolean;

        internal var size:flash.geom.Point;

        internal var autojoinGroup:Boolean;

        public var showNewcomerChannel:Boolean;

        public var textListener:Function;

        public var trimClanName:Boolean=true;

        internal var userId:int;

        public static var DEBUG:Boolean=false;
    }
}
