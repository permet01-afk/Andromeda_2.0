package net.bigpoint.as3chat 
{
    public class ScalableRoom extends net.bigpoint.as3chat.Room
    {
        public function ScalableRoom(arg1:int, arg2:String, arg3:int, arg4:int, arg5:int, arg6:Boolean)
        {
            super(arg1, arg2, arg3, arg4, arg5, arg6);
            this.childRoomId = -1;
            return;
        }

        public override function getRoomName():String
        {
            if (this.childRoomId == -1) 
            {
                return super.getRoomName();
            }
            return super.getRoomName() + " " + this.childRoomName;
        }

        public override function getRoomId():int
        {
            if (this.childRoomId == -1) 
            {
                return super.getRoomId();
            }
            return this.childRoomId;
        }

        public function getChildRoomName():String
        {
            return this.childRoomName;
        }

        public function setChildRoomId(arg1:int):void
        {
            this.childRoomId = arg1;
            return;
        }

        public function setChildRoomName(arg1:String):void
        {
            this.childRoomName = arg1;
            return;
        }

        public function getRoomFirstRun():Boolean
        {
            return this.firstRun;
        }

        public function setRoomFirstRun(arg1:Boolean):void
        {
            this.firstRun = arg1;
            return;
        }

        internal var childRoomId:int;

        internal var childRoomName:String;

        public var firstRun:Boolean=true;
    }
}
