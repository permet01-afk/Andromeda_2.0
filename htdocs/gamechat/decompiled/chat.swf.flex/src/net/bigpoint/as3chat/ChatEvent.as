package net.bigpoint.as3chat 
{
    import flash.events.*;
    
    public class ChatEvent extends flash.events.Event
    {
        public function ChatEvent(arg1:String, arg2:Boolean=false, arg3:Boolean=false)
        {
            super(arg1, arg2, arg3);
            return;
        }

        public static const MessageEvent:String="MessageEvent.TEXT_RECEIVED";

        public var username:String;

        public var message:String;

        public var clanTag:String;

        public var usertype:int;
    }
}
