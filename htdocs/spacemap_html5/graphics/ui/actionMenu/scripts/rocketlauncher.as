package
{
   import flash.display.MovieClip;
   
   [Embed(source="/_assets/assets.swf", symbol="symbol136")]
   public dynamic class rocketlauncher extends MovieClip
   {
      
      public var selectedLauncherRocket:MovieClip;
      
      public var slots:MovieClip;
      
      public function rocketlauncher()
      {
         super();
         addFrameScript(0,frame1);
      }
      
      internal function frame1() : *
      {
      }
   }
}

