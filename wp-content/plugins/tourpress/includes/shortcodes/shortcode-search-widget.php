<?php

function tourpress_search_widget($atts, $content)
{
    $terms = get_categories( array(
      'taxonomy' => 'location',
      'hide_empty' => true,
    ));
    echo "<script>console.log('".json_encode($terms)."');</script>";
    ob_start();
    ?>
<div class="xpl-search-widget">
  <div class="xpl-search-tabs">
    <div class="xpl-search-tabs-inner">
      <div data-name="tours" data-target="#search-tours" class="xpl-search-tab active"><a class="xpl-search-tours">Tours</a></div>
      <div data-name="hotels" data-target="#search-hotels" class="xpl-search-tab"><a class="xpl-search-hotels">Hotels</a></div>
      <div data-name="car-hire" data-target="#search-car-hire" class="xpl-search-tab"><a class="xpl-search-car-hire">Car Hire</a></div>
<!--       <div data-name="cruises" data-target="#search-cruises" class="xpl-search-tab"><a class="xpl-search-cruises">Cruises</a></div>   -->
      <div data-name="cruises"  data-target="#search-cruises"  class="xpl-search-tab"><a class="xpl-search-cruises" style="opacity:0.4;" disabled>Cruises</a></div>  
    </div>
  </div>   
  <div class="xpl-search-contents">
    <div class="xpl-search-content active" id="search-tours">
      <?php echo xpl_form_content_search_tours();?>
    </div>  
    <div class="xpl-search-content" id="search-hotels">
      
      <div class="filter-wrap filter-horizontal clearfix" id="vfilter">
        <form class="" method="POST" action="/hotels/">
            <div class="field-group">
              <label>Location</label>
              <select placeholder="Choose Location">
                  
                  <option>Adelaide, SA (ADL)</option>
                  <option>Albany, Margaret River &amp; The South West , WA (ALH)</option>
                  <option>Alice Springs, NT (ASP)</option>
                  <option>Arnhemland, NT (ARN)</option>
                  <option>Ayers Rock, NT (AYQ)</option>
                  <option>Barossa Valley, SA</option>
                  <option>Broome &amp; The Kimberley, WA (KIMB)</option>
                  <option>Broome, Broome &amp; The Kimberley, WA (BME)</option>
                  <option>Bullo River Station, Kununurra, Broome &amp; The Kimberley, WA (BRS)</option>
                  <option>Bunbury, Margaret River &amp; The South West , WA (BUY)</option>
                  <option>Bungle Bungles, Broome &amp; The Kimberley, WA (BBS)</option>
                  <option>Busselton, Margaret River &amp; The South West , WA (BSL)</option>
                  <option>Cairns, QLD (CNS)</option>
                  <option>Carnarvon, Ningaloo &amp; Coral Coast, WA (CVQ)</option>
                  <option>Cervantes, Ningaloo &amp; Coral Coast, WA (CVA)</option>
                  <option>Christmas Island (XCH)</option>
                  <option>Clare, SA</option>
                  <option>Cocos Keeling Islands (CCK)</option>
                  <option>Coober Pedy, SA (CPD)</option>
                  <option>Coonawarra, SA</option>
                  <option>Coral Bay, Ningaloo &amp; Coral Coast, WA (CRB)</option>
                  <option>Darwin, NT (DRW)</option>
                  <option>Denham, Monkey Mia, Ningaloo &amp; Coral Coast, WA (DHM)</option>
                  <option>Derby, Broome &amp; The Kimberley, WA (DRB)</option>
                  <option>Dunsborough, Margaret River &amp; The South West , WA (DUN)</option>
                  <option>Esperance, The Goldfields &amp; Great Southern, WA (EPR)</option>
                  <option>Exmouth, Ningaloo &amp; Coral Coast, WA (LEA)</option>
                  <option>Fitzroy Crossing, Broome &amp; The Kimberley, WA (FIZ)</option>
                  <option>Flinders Ranges, SA</option>
                  <option>Fremantle, Perth, WA (FRE)</option>
                  <option>Geraldton, Ningaloo &amp; Coral Coast, WA (GET)</option>
                  <option>Gibb River Road, Broome &amp; The Kimberley, WA (GRR)</option>
                  <option>Groote Eylandt, NT (GTE)</option>
                  <option>Hahndorf, SA</option>
                  <option>Halls Creek, Broome &amp; The Kimberley, WA (HCQ)</option>
                  <option>Hyden, The Goldfields &amp; Great Southern, WA (HYD)</option>
                  <option>Kakadu, NT (KAK)</option>
                  <option>Kalbarri, Ningaloo &amp; Coral Coast, WA (KAX)</option>
                  <option>Kalgoorlie, The Goldfields &amp; Great Southern, WA (KGI)</option>
                  <option>Kangaroo Island, SA (KGC)</option>
                  <option>Karijini &amp; The Pilbara , WA (PIL)</option>
                  <option>Karijini, Karijini &amp; The Pilbara , WA (KJI)</option>
                  <option>Karratha, Karijini &amp; The Pilbara , WA (KTA)</option>
                  <option>Katherine, NT (KTR)</option>
                  <option>Kings Canyon, NT (KBJ)</option>
                  <option>Kingscote, SA</option>
                  <option>Kununurra, Broome &amp; The Kimberley, WA (KNX)</option>
                  <option>Litchfield, NT (LIT)</option>
                  <option>Mandurah, Perth, WA (MAH)</option>
                  <option>Margaret River &amp; The South West , WA (SW)</option>
                  <option>Margaret River, Margaret River &amp; The South West , WA (MQZ)</option>
                  <option>Mitchell Plateau, Broome &amp; The Kimberley, WA (MIH)</option>
                  <option>Monkey Mia, Ningaloo &amp; Coral Coast, WA (MJK)</option>
                  <option>Ningaloo &amp; Coral Coast, WA (CC)</option>
                  <option>Northern Territory (NT)</option>
                  <option>Onslow, Karijini &amp; The Pilbara , WA (ONS)</option>
                  <option>Pemberton, Margaret River &amp; The South West , WA (PEM)</option>
                  <option>Perth City, Perth, WA (PTH)</option>
                  <option>Perth, WA (PER)</option>
                  <option>Port Augusta, SA (PUG)</option>
                  <option>Port Douglas, QLD (PTI)</option>
                  <option>Port Hedland, Karijini &amp; The Pilbara , WA (PHE)</option>
                  <option>Port Lincoln, SA (PLO)</option>
                  <option>Queensland (QLD)</option>
                  <option>Rottnest Island, Perth, WA (RTS)</option>
                  <option>South Australia (SA)</option>
                  <option>Sydney (SYD)</option>
                  <option>The Goldfields &amp; Great Southern, WA (GOL)</option>
                  <option>Victor Harbor, SA</option>
                  <option>Western Australia (WA)</option>
                  <option>Yallingup, Margaret River &amp; The South West , WA (YAL)</option>

              </select>
            </div>
            <div class="field-group">
                <label>Date In</label>
                <div class="input-wrap input-calendar" aria-label="Use the arrow keys to pick a date"><input name="date_in" type="text" id="check-in-v" class="input-calendar"></div>
            </div>

            <div class="field-group">
                <label>Date Out</label>
                <div class="input-wrap input-calendar" aria-label="Use the arrow keys to pick a date"><input name="date_out" type="text" id="check-out-v" class="input-calendar"></div>
            </div>

            <div class="field-group field-group-sm">
                <label>Nights</label>
                <input type="number" value="7">
                <!--             <span class="field-unit">Nights</span> -->
            </div>

            <div class="field-group field-group-sm">
                <label>Adults</label>
                <input type="number" name="adults" value="2">
            </div>

            <div class="field-group field-group-sm">
                <label>Child</label>
                <input type="number" name="children" value="0">
            </div>

            <div class="field-group submit-wrap">
                <label>&nbsp;</label>
                <button type="submit">SEARCH</button>
            </div>
        </form>
      </div>
      
    </div>  
    <div class="xpl-search-content" id="search-car-hire">
      <?php echo xpl_form_content_car_hire(); ?> 
    </div>  
    <div class="xpl-search-content" id="search-cruises"><i>UNDER CONSTRUCTION</i></div>  
  </div>
</div>
    
    <?php
    echo xpl_search_widget_js();
    $ret = ob_get_clean();
    return $ret;
}

add_shortcode('xpl_search_widget', 'tourpress_search_widget');

/*--------------------------------------
 SEARCH TOURS
---------------------------------------*/
function xpl_form_content_search_tours(){
  ?>
<div class="filter-wrap filter-horizontal clearfix" id="vfilter" style="max-width:930px;">
        <form class="xpl-search-form" method="POST" action="/tours/">
            <div class="field-group">
              <label>Location</label>
              <select placeholder="Choose Location">
                  
                  <option>Adelaide, SA (ADL)</option>
                  <option>Albany, Margaret River &amp; The South West , WA (ALH)</option>
                  <option>Alice Springs, NT (ASP)</option>
                  <option>Arnhemland, NT (ARN)</option>
                  <option>Ayers Rock, NT (AYQ)</option>
                  <option>Barossa Valley, SA</option>
                  <option>Broome &amp; The Kimberley, WA (KIMB)</option>
                  <option>Broome, Broome &amp; The Kimberley, WA (BME)</option>
                  <option>Bullo River Station, Kununurra, Broome &amp; The Kimberley, WA (BRS)</option>
                  <option>Bunbury, Margaret River &amp; The South West , WA (BUY)</option>
                  <option>Bungle Bungles, Broome &amp; The Kimberley, WA (BBS)</option>
                  <option>Busselton, Margaret River &amp; The South West , WA (BSL)</option>
                  <option>Cairns, QLD (CNS)</option>
                  <option>Carnarvon, Ningaloo &amp; Coral Coast, WA (CVQ)</option>
                  <option>Cervantes, Ningaloo &amp; Coral Coast, WA (CVA)</option>
                  <option>Christmas Island (XCH)</option>
                  <option>Clare, SA</option>
                  <option>Cocos Keeling Islands (CCK)</option>
                  <option>Coober Pedy, SA (CPD)</option>
                  <option>Coonawarra, SA</option>
                  <option>Coral Bay, Ningaloo &amp; Coral Coast, WA (CRB)</option>
                  <option>Darwin, NT (DRW)</option>
                  <option>Denham, Monkey Mia, Ningaloo &amp; Coral Coast, WA (DHM)</option>
                  <option>Derby, Broome &amp; The Kimberley, WA (DRB)</option>
                  <option>Dunsborough, Margaret River &amp; The South West , WA (DUN)</option>
                  <option>Esperance, The Goldfields &amp; Great Southern, WA (EPR)</option>
                  <option>Exmouth, Ningaloo &amp; Coral Coast, WA (LEA)</option>
                  <option>Fitzroy Crossing, Broome &amp; The Kimberley, WA (FIZ)</option>
                  <option>Flinders Ranges, SA</option>
                  <option>Fremantle, Perth, WA (FRE)</option>
                  <option>Geraldton, Ningaloo &amp; Coral Coast, WA (GET)</option>
                  <option>Gibb River Road, Broome &amp; The Kimberley, WA (GRR)</option>
                  <option>Groote Eylandt, NT (GTE)</option>
                  <option>Hahndorf, SA</option>
                  <option>Halls Creek, Broome &amp; The Kimberley, WA (HCQ)</option>
                  <option>Hyden, The Goldfields &amp; Great Southern, WA (HYD)</option>
                  <option>Kakadu, NT (KAK)</option>
                  <option>Kalbarri, Ningaloo &amp; Coral Coast, WA (KAX)</option>
                  <option>Kalgoorlie, The Goldfields &amp; Great Southern, WA (KGI)</option>
                  <option>Kangaroo Island, SA (KGC)</option>
                  <option>Karijini &amp; The Pilbara , WA (PIL)</option>
                  <option>Karijini, Karijini &amp; The Pilbara , WA (KJI)</option>
                  <option>Karratha, Karijini &amp; The Pilbara , WA (KTA)</option>
                  <option>Katherine, NT (KTR)</option>
                  <option>Kings Canyon, NT (KBJ)</option>
                  <option>Kingscote, SA</option>
                  <option>Kununurra, Broome &amp; The Kimberley, WA (KNX)</option>
                  <option>Litchfield, NT (LIT)</option>
                  <option>Mandurah, Perth, WA (MAH)</option>
                  <option>Margaret River &amp; The South West , WA (SW)</option>
                  <option>Margaret River, Margaret River &amp; The South West , WA (MQZ)</option>
                  <option>Mitchell Plateau, Broome &amp; The Kimberley, WA (MIH)</option>
                  <option>Monkey Mia, Ningaloo &amp; Coral Coast, WA (MJK)</option>
                  <option>Ningaloo &amp; Coral Coast, WA (CC)</option>
                  <option>Northern Territory (NT)</option>
                  <option>Onslow, Karijini &amp; The Pilbara , WA (ONS)</option>
                  <option>Pemberton, Margaret River &amp; The South West , WA (PEM)</option>
                  <option>Perth City, Perth, WA (PTH)</option>
                  <option>Perth, WA (PER)</option>
                  <option>Port Augusta, SA (PUG)</option>
                  <option>Port Douglas, QLD (PTI)</option>
                  <option>Port Hedland, Karijini &amp; The Pilbara , WA (PHE)</option>
                  <option>Port Lincoln, SA (PLO)</option>
                  <option>Queensland (QLD)</option>
                  <option>Rottnest Island, Perth, WA (RTS)</option>
                  <option>South Australia (SA)</option>
                  <option>Sydney (SYD)</option>
                  <option>The Goldfields &amp; Great Southern, WA (GOL)</option>
                  <option>Victor Harbor, SA</option>
                  <option>Western Australia (WA)</option>
                  <option>Yallingup, Margaret River &amp; The South West , WA (YAL)</option>

              </select>
            </div>
            <div class="field-group">
                <label>Pickup Date</label>
                <div class="input-wrap input-calendar" aria-label="Use the arrow keys to pick a date"><input name="date_in" type="text" id="check-in-v" class="input-calendar"></div>
            </div>

            <div class="field-group field-group-sm">
                <label>Adults</label>
                <input type="number" name="adults" value="2">
            </div>

            <div class="field-group field-group-sm">
                <label>Child</label>
                <input type="number" name="children" value="0">
            </div>

            <div class="field-group submit-wrap">
                <label>&nbsp;</label>
                <button type="submit">SEARCH</button>
            </div>
        </form>
      </div>
  <?php
}

function xpl_form_content_car_hire(){
  ?>
    <div class="filter-wrap filter-horizontal clearfix" id="vfilter">
        <form class="" method="POST" action="/car-hire/">
            <div class="field-group">
              <label>Pickup Location</label>
              <select placeholder="Choose Location">
                  
                  <option>Adelaide, SA (ADL)</option>
                  <option>Albany, Margaret River &amp; The South West , WA (ALH)</option>
                  <option>Alice Springs, NT (ASP)</option>
                  <option>Arnhemland, NT (ARN)</option>
                  <option>Ayers Rock, NT (AYQ)</option>
                  <option>Barossa Valley, SA</option>
                  <option>Broome &amp; The Kimberley, WA (KIMB)</option>
                  <option>Broome, Broome &amp; The Kimberley, WA (BME)</option>
                  <option>Bullo River Station, Kununurra, Broome &amp; The Kimberley, WA (BRS)</option>
                  <option>Bunbury, Margaret River &amp; The South West , WA (BUY)</option>
                  <option>Bungle Bungles, Broome &amp; The Kimberley, WA (BBS)</option>
                  <option>Busselton, Margaret River &amp; The South West , WA (BSL)</option>
                  <option>Cairns, QLD (CNS)</option>
                  <option>Carnarvon, Ningaloo &amp; Coral Coast, WA (CVQ)</option>
                  <option>Cervantes, Ningaloo &amp; Coral Coast, WA (CVA)</option>
                  <option>Christmas Island (XCH)</option>
                  <option>Clare, SA</option>
                  <option>Cocos Keeling Islands (CCK)</option>
                  <option>Coober Pedy, SA (CPD)</option>
                  <option>Coonawarra, SA</option>
                  <option>Coral Bay, Ningaloo &amp; Coral Coast, WA (CRB)</option>
                  <option>Darwin, NT (DRW)</option>
                  <option>Denham, Monkey Mia, Ningaloo &amp; Coral Coast, WA (DHM)</option>
                  <option>Derby, Broome &amp; The Kimberley, WA (DRB)</option>
                  <option>Dunsborough, Margaret River &amp; The South West , WA (DUN)</option>
                  <option>Esperance, The Goldfields &amp; Great Southern, WA (EPR)</option>
                  <option>Exmouth, Ningaloo &amp; Coral Coast, WA (LEA)</option>
                  <option>Fitzroy Crossing, Broome &amp; The Kimberley, WA (FIZ)</option>
                  <option>Flinders Ranges, SA</option>
                  <option>Fremantle, Perth, WA (FRE)</option>
                  <option>Geraldton, Ningaloo &amp; Coral Coast, WA (GET)</option>
                  <option>Gibb River Road, Broome &amp; The Kimberley, WA (GRR)</option>
                  <option>Groote Eylandt, NT (GTE)</option>
                  <option>Hahndorf, SA</option>
                  <option>Halls Creek, Broome &amp; The Kimberley, WA (HCQ)</option>
                  <option>Hyden, The Goldfields &amp; Great Southern, WA (HYD)</option>
                  <option>Kakadu, NT (KAK)</option>
                  <option>Kalbarri, Ningaloo &amp; Coral Coast, WA (KAX)</option>
                  <option>Kalgoorlie, The Goldfields &amp; Great Southern, WA (KGI)</option>
                  <option>Kangaroo Island, SA (KGC)</option>
                  <option>Karijini &amp; The Pilbara , WA (PIL)</option>
                  <option>Karijini, Karijini &amp; The Pilbara , WA (KJI)</option>
                  <option>Karratha, Karijini &amp; The Pilbara , WA (KTA)</option>
                  <option>Katherine, NT (KTR)</option>
                  <option>Kings Canyon, NT (KBJ)</option>
                  <option>Kingscote, SA</option>
                  <option>Kununurra, Broome &amp; The Kimberley, WA (KNX)</option>
                  <option>Litchfield, NT (LIT)</option>
                  <option>Mandurah, Perth, WA (MAH)</option>
                  <option>Margaret River &amp; The South West , WA (SW)</option>
                  <option>Margaret River, Margaret River &amp; The South West , WA (MQZ)</option>
                  <option>Mitchell Plateau, Broome &amp; The Kimberley, WA (MIH)</option>
                  <option>Monkey Mia, Ningaloo &amp; Coral Coast, WA (MJK)</option>
                  <option>Ningaloo &amp; Coral Coast, WA (CC)</option>
                  <option>Northern Territory (NT)</option>
                  <option>Onslow, Karijini &amp; The Pilbara , WA (ONS)</option>
                  <option>Pemberton, Margaret River &amp; The South West , WA (PEM)</option>
                  <option>Perth City, Perth, WA (PTH)</option>
                  <option>Perth, WA (PER)</option>
                  <option>Port Augusta, SA (PUG)</option>
                  <option>Port Douglas, QLD (PTI)</option>
                  <option>Port Hedland, Karijini &amp; The Pilbara , WA (PHE)</option>
                  <option>Port Lincoln, SA (PLO)</option>
                  <option>Queensland (QLD)</option>
                  <option>Rottnest Island, Perth, WA (RTS)</option>
                  <option>South Australia (SA)</option>
                  <option>Sydney (SYD)</option>
                  <option>The Goldfields &amp; Great Southern, WA (GOL)</option>
                  <option>Victor Harbor, SA</option>
                  <option>Western Australia (WA)</option>
                  <option>Yallingup, Margaret River &amp; The South West , WA (YAL)</option>

              </select>
            </div>
          
            <div class="field-group">
                <label>Pickup Date</label>
                <div class="input-wrap input-calendar" aria-label="Use the arrow keys to pick a date"><input name="date_in" type="text" id="check-in-v" class="input-calendar"></div>
            </div>

            <div class="field-group">
                <label>Drop-off Date</label>
                <div class="input-wrap input-calendar" aria-label="Use the arrow keys to pick a date"><input name="date_out" type="text" id="check-out-v" class="input-calendar"></div>
            </div>
            
            <div class="field-group field-group-sm">
                <label>Duration</label>
                <input type="number" value="7">
                <!--             <span class="field-unit">Nights</span> -->
            </div>

            <div class="field-group field-group-sm">
                <label>Adults</label>
                <input type="number" name="adults" value="2">
            </div>

            <div class="field-group field-group-sm">
                <label>Child</label>
                <input type="number" name="children" value="0">
            </div>

            <div class="field-group submit-wrap">
                <label>&nbsp;</label>
                <button type="submit">SEARCH</button>
            </div>
        </form>
      </div>
  <?php
}

function xpl_search_widget_js(){
  ?>
<script>

  (function($) {
    $('.xpl-search-widget .xpl-search-tab > a').on('click',function(){
     // var formAction = $(this).closest('.xpl-search-widget').find('.xpl-search-tab.active').attr('data-name')+"#vFilter";
     // $(this).closest('.xpl-search-widget').find('');
      
      $(this).closest('.xpl-search-widget').find('.xpl-search-tab.active,.xpl-search-content.active').removeClass('active');
      $(this).closest('.xpl-search-tab').addClass('active');
      
      
      
      var target = $(this).closest('.xpl-search-tab').attr('data-target');
      $(this).closest('.xpl-search-widget').find('.xpl-search-content'+target).addClass('active');
    });  
  }(window.jQuery || window.$));      

</script>
  <?php
}