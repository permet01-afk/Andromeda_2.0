package net.bigpoint.flashcorelib.resources 
{
    import flash.events.*;
    
    public class MediaHandlerEvent extends flash.events.Event
    {
        public function MediaHandlerEvent(arg1:String, arg2:Boolean=false, arg3:Boolean=false)
        {
            super(arg1, arg2, arg3);
            return;
        }

        public function setMediaType(arg1:int):void
        {
            this.mediaType = arg1;
            return;
        }

        public function setCnt(arg1:int):void
        {
            this.cnt = arg1;
            return;
        }

        public function setMaxCnt(arg1:int):void
        {
            this.maxCnt = arg1;
            return;
        }

        public function getMediaType():int
        {
            return this.mediaType;
        }

        public function getCnt():int
        {
            return this.cnt;
        }

        public function getmaxCnt():int
        {
            return this.maxCnt;
        }

        public function getErrorMsg():String
        {
            return this.errorMsg;
        }

        public function setErrorMsg(arg1:String):void
        {
            this.errorMsg = arg1;
            return;
        }

        public function getName():String
        {
            return this.name;
        }

        public function setName(arg1:String):void
        {
            this.name = arg1;
            return;
        }

        public static const MEDIALOADED:String="MediaHandlerEvent.onMediaLoaded";

        public static const ALLMEDIALOADED:String="MediaHandlerEvent.onAllMediaLoaded";

        public static const ERROR_LOADING_MEDIA:String="MediaHandlerEvent.onErrorLoadingMedia";

        internal var name:String;

        internal var mediaType:int;

        internal var cnt:int;

        internal var maxCnt:int;

        internal var errorMsg:String;
    }
}
