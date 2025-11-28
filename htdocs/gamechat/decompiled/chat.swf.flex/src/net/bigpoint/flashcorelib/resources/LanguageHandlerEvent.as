package net.bigpoint.flashcorelib.resources 
{
    import flash.events.*;
    
    public class LanguageHandlerEvent extends flash.events.Event
    {
        public function LanguageHandlerEvent(arg1:String, arg2:Boolean=false, arg3:Boolean=false)
        {
            super(arg1, arg2, arg3);
            return;
        }

        public static const LANGUAGELOADED:String="LanguageHandlerEvent.onLanguageLoaded";
    }
}
