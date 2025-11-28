package pld8
{
   import flash.display.BitmapData;
   
   [Embed(source="/_assets/32_pld8.png.png")]
   public dynamic class png extends BitmapData
   {
      
      public function png(param1:int = 32, param2:int = 35)
      {
         super(param1,param2);
      }
   }
}

