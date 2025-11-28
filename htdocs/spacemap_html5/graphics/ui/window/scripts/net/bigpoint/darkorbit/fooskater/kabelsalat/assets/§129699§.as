package net.bigpoint.darkorbit.fooskater.kabelsalat.assets
{
   import flash.utils.ByteArray;
   import net.bigpoint.darkorbit.fooskater.kabelsalat.KabelSpeicher;
   
   public class §129699§
   {
      
      var _temp_1:* = true;
      var _loc1_:Boolean = false;
      var _loc2_:Boolean = _temp_1;
      §§push(§§findproperty(§_-A§));
      §§push(1904);
      if(!_loc2_)
      {
         §§push(---(§§pop() + 28) * 95);
      }
      §§push(§§pop() * 128);
      §§push(10);
      if(_loc1_)
      {
         §§push(-(§§pop() * 54) - 92 + 1);
      }
      
      private static var §_-A§:uint = §§pop() + §§pop();
      
      private var §_-9§:String = "10640323745300642110";
      
      private var §_-1§:String = "33338076815427577960";
      
      private var §_-2§:KabelSpeicher;
      
      public function §129699§()
      {
         var _temp_1:* = true;
         var _loc1_:Boolean = false;
         var _loc2_:Boolean = _temp_1;
         super();
      }
      
      public static function getMemoryPosition() : uint
      {
         var _temp_1:* = false;
         var _loc1_:Boolean = true;
         var _loc2_:Boolean = _temp_1;
         return §_-A§;
      }
      
      public function init(param1:ByteArray, param2:String) : void
      {
         var _temp_1:* = true;
         var _loc3_:* = false;
         var _loc4_:* = _temp_1;
         this.§_-2§ = new KabelSpeicher(param1);
         if(_loc3_)
         {
            var _temp_3:* = param2;
            var _temp_2:* = param1;
            this = this;
            param1 = _temp_2;
            param2 = _temp_3;
            while(true)
            {
               this.§_-2§.send(this.§_-9§,this.§_-1§);
               if(!_loc3_)
               {
                  break;
               }
               var _temp_5:* = _loc4_;
               var _temp_4:* = _loc4_;
               _loc4_ = _loc4_;
               _loc4_ = _temp_4;
               _loc4_ = _temp_5;
            }
            addr0062:
            return;
            addr0022:
         }
         while(true)
         {
            this.§_-2§.connect(§_-A§);
            if(!_loc4_)
            {
               var _temp_7:* = this;
               var _temp_6:* = _loc4_;
               _loc3_ = _loc3_;
               _loc4_ = _temp_6;
               this = _temp_7;
               break;
            }
            §§goto(addr0022);
         }
         §§goto(addr0062);
      }
   }
}

