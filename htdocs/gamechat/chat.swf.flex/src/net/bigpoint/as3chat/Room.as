package net.bigpoint.as3chat 
{
    public class Room extends Object
    {
        public function Room(arg1:int, arg2:String, arg3:int, arg4:int, arg5:int, arg6:Boolean)
        {
            this.messages = new Array();
            super();
            this.roomId = arg1;
            this.roomName = arg2;
            this.tabOrder = arg3;
            this.companyId = arg4;
            this.roomType = arg5;
            this.themeId = -1;
            this.sectorId = -1;
            this._newcomerRoom = arg6;
            return;
        }

        
        {
            NORMAL_ROOM = 0;
            PRIVATE_ROOM = 1;
            CLAN_ROOM = 2;
            SUPPORT_ROOM = 3;
            GROUP_ROOM = 4;
            DYNAMIC_ROOM = 5;
            SCALABLE_ROOM_PARENT = 6;
            SCALABLE_ROOM_CHILD = 7;
            SECTOR_ROOM = 8;
        }

        public function getCreatorId():int
        {
            return this.creatorId;
        }

        public function setCreatorId(arg1:int):void
        {
            this.creatorId = arg1;
            return;
        }

        public function getCreatorName():String
        {
            return this.creatorName;
        }

        public function getRoomName():String
        {
            return this.roomName;
        }

        public function setCreatorName(arg1:String):void
        {
            this.creatorName = arg1;
            return;
        }

        public function isInvited():Boolean
        {
            return this.invited;
        }

        public function setInvited(arg1:Boolean):void
        {
            this.invited = arg1;
            return;
        }

        public function get groupID():int
        {
            return this._groupID;
        }

        public function set groupID(arg1:int):void
        {
            this._groupID = arg1;
            return;
        }

        public function setSectorId(arg1:int):void
        {
            this.sectorId = arg1;
            return;
        }

        public function getSectorId():int
        {
            return this.sectorId;
        }

        public function get newcomerRoom():Boolean
        {
            return this._newcomerRoom;
        }

        public function addMessage(arg1:net.bigpoint.as3chat.Message):void
        {
            if (this.messages.length > 150) 
            {
                this.messages.shift();
            }
            this.messages.push(arg1);
            return;
        }

        public function getAllMessages():String
        {
            var loc3:*=null;
            var loc1:*="";
            var loc2:*=0;
            while (loc2 < this.messages.length) 
            {
                loc3 = this.messages[loc2];
                loc1 = loc1 + loc3.getHTML();
                ++loc2;
            }
            return loc1;
        }

        public function getTabOrder():int
        {
            return this.tabOrder;
        }

        public function getRoomType():int
        {
            return this.roomType;
        }

        public function getCompanyId():int
        {
            return this.companyId;
        }

        public function getRoomId():int
        {
            return this.roomId;
        }

        public function getThemeId():int
        {
            return this.themeId;
        }

        public function setThemeId(arg1:int):void
        {
            this.themeId = arg1;
            return;
        }

        internal var roomId:int;

        internal var roomName:String;

        public var tabOrder:int;

        internal var roomType:int;

        internal var themeId:int;

        internal var sectorId:int;

        internal var _groupID:int;

        internal var companyId:int;

        internal var creatorName:String;

        internal var creatorId:int;

        internal var messages:Array;

        internal var _newcomerRoom:Boolean;

        public static var NORMAL_ROOM:int=0;

        public static var PRIVATE_ROOM:int=1;

        public static var CLAN_ROOM:int=2;

        public static var SUPPORT_ROOM:int=3;

        internal var invited:Boolean;

        public static var DYNAMIC_ROOM:int=5;

        public static var SCALABLE_ROOM_PARENT:int=6;

        public static var SCALABLE_ROOM_CHILD:int=7;

        public static var SECTOR_ROOM:int=8;

        public static var GROUP_ROOM:int=4;
    }
}
