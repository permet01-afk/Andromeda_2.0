package net.bigpoint.flashcorelib.resources 
{
    public class LanguageHandlerProxy extends Object
    {
        public function LanguageHandlerProxy()
        {
            this.variables = new Array();
            super();
            this.init();
            return;
        }

        internal function init():void
        {
            this.addVar("globalchat.login.userAlreadyExist", "Login fehlgeschlagen. Der User exstiert bereits.");
            this.addVar("globalchat.chat.server", "Server");
            this.addVar("globalchat.chat.tip", "Tipp");
            this.addVar("globalchat.chat.roomNameNotAllowed", "room name not allowed!");
            return;
        }

        internal function addVar(arg1:String, arg2:String):void
        {
            this.variables[arg1] = arg2;
            return;
        }

        public var variables:Array;
    }
}
