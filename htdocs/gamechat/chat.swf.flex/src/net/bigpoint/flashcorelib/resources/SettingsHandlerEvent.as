package net.bigpoint.flashcorelib.resources 
{
    import flash.events.*;
    
    public class SettingsHandlerEvent extends flash.events.Event
    {
        public function SettingsHandlerEvent(arg1:String, arg2:Boolean=false, arg3:Boolean=false)
        {
            super(arg1, arg2, arg3);
            return;
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

        public static const SETTINGSLOADED:String="SettingsHandlerEvent.onSettingsLoaded";

        public static const ERROR_LOADING_SETTINGS:String="SettingsHandlerEvent.onErrorLoadingSettings";

        internal var errorMsg:String;
    }
}
