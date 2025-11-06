<div class='container-fluid justify-content-center'>
  <div class='checkbox'>
  <button  type='button' class='btn btn-outline-primary btn-sm' onclick='set_township(32);'>國中小全選</button> <button  type='button' class='btn btn-outline-primary btn-sm' onclick='set_township(64);'>學校全選</button> <button  type='button' class='btn btn-outline-danger btn-sm' onclick='set_none(64)'>學校全不選</button>
      <hr>
  <button  type='button' class='btn btn-primary btn-sm' onclick='set_township(8);'>高中全選</button> <button  type='button' class='btn btn-outline-danger btn-sm' onclick='set_none(8)'>高中全不選</button><br>
      <label for='sel_school_16' class="text-success"><input type='checkbox' id='sel_school_16' name='sel_school[]' value='16' @if(is_array(old('sel_school')) && in_array(16, old('sel_school'))) checked @endif class='ts_4 z_4_1 ts_8 ts_32 ts_64' onchange="change_another2()">彰化藝術高中</label>
      <label for='sel_school_241' class="text-secondary"><input type='checkbox' id='sel_school_241' name='sel_school[]' value='241' @if(is_array(old('sel_school')) && in_array(241, old('sel_school'))) checked @endif class='ts_4 z_4_8 ts_8 ts_32 ts_64' onclick="change_another3()">二林高中</label>
      <label for='sel_school_244' class="text-secondary"><input type='checkbox' id='sel_school_244' name='sel_school[]' value='244' @if(is_array(old('sel_school')) && in_array(244, old('sel_school'))) checked @endif class='ts_4 z_4_3 ts_8 ts_32 ts_64' onclick="change_another4()">和美高中</label>
      <label for='sel_school_243' class="text-secondary"><input type='checkbox' id='sel_school_243' name='sel_school[]' value='243' @if(is_array(old('sel_school')) && in_array(243, old('sel_school'))) checked @endif class='ts_4 z_4_6 ts_8 ts_32 ts_64' onclick="change_another5()">成功高中</label>
      <label for='sel_school_245' class="text-secondary"><input type='checkbox' id='sel_school_245' name='sel_school[]' value='245' @if(is_array(old('sel_school')) && in_array(245, old('sel_school'))) checked @endif class='ts_4 z_4_5 ts_8 ts_32 ts_64' onclick="change_another6()">田中高中</label>
<hr>
      <button  type='button' class='btn btn-primary btn-sm' onclick='set_township(4);'>國中全選</button> <button  type='button' class='btn btn-outline-danger btn-sm' onclick='set_none(4)'>國中全不選</button><br>
      <button  type='button' class='btn btn-success btn-sm my-1' onclick='set_zone(4,1);'>彰化區</button>
      <label for='sel_school_16_2' class="text-success"><input type='checkbox' id='sel_school_16_2' disabled>彰化藝術高中(國中部)</label>
      <label for='sel_school_17'><input type='checkbox' id='sel_school_17' name='sel_school[]' value='17' @if(is_array(old('sel_school')) && in_array(17, old('sel_school'))) checked @endif class='ts_4 z_4_1 ts_32 ts_64'>陽明國中</label>
      <label for='sel_school_18'><input type='checkbox' id='sel_school_18' name='sel_school[]' value='18' @if(is_array(old('sel_school')) && in_array(18, old('sel_school'))) checked @endif class='ts_4 z_4_1 ts_32 ts_64'>彰安國中</label>
      <label for='sel_school_19'><input type='checkbox' id='sel_school_19' name='sel_school[]' value='19' @if(is_array(old('sel_school')) && in_array(19, old('sel_school'))) checked @endif class='ts_4 z_4_1 ts_32 ts_64'>彰德國中</label>
      <label for='sel_school_37'><input type='checkbox' id='sel_school_37' name='sel_school[]' value='37' @if(is_array(old('sel_school')) && in_array(37, old('sel_school'))) checked @endif class='ts_4 z_4_1 ts_32 ts_64'>芬園國中</label>
      <label for='sel_school_44'><input type='checkbox' id='sel_school_44' name='sel_school[]' value='44' @if(is_array(old('sel_school')) && in_array(44, old('sel_school'))) checked @endif class='ts_4 z_4_1 ts_32 ts_64'>花壇國中</label>
      <label for='sel_school_20'><input type='checkbox' id='sel_school_20' name='sel_school[]' value='20' @if(is_array(old('sel_school')) && in_array(20, old('sel_school'))) checked @endif class='ts_4 z_4_1 ts_32 ts_64'>彰興國中</label>
      <label for='sel_school_21'><input type='checkbox' id='sel_school_21' name='sel_school[]' value='21' @if(is_array(old('sel_school')) && in_array(21, old('sel_school'))) checked @endif class='ts_4 z_4_1 ts_32 ts_64'>彰泰國中</label>
      <label for='sel_school_242'><input type='checkbox' id='sel_school_242' name='sel_school[]' value='242' @if(is_array(old('sel_school')) && in_array(242, old('sel_school'))) checked @endif class='ts_500 ts_2 ts_4 z_4_1 ts_32 ts_64' onclick="change_another7()">信義國中(小)</label>
      <label for='sel_school_249'><input type='checkbox' id='sel_school_249' name='sel_school[]' value='249' @if(is_array(old('sel_school')) && in_array(249, old('sel_school'))) checked @endif class='ts_4 z_4_1 ts_32 ts_64'><span class="text-primary">私立正德高中(國中部)</span></label>
      <label for='sel_school_250'><input type='checkbox' id='sel_school_250' name='sel_school[]' value='250' @if(is_array(old('sel_school')) && in_array(250, old('sel_school'))) checked @endif class='ts_4 z_4_1 ts_32 ts_64'><span class="text-primary">私立精誠高中(國中部)</span></label>
<br> <button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_zone(4,2);'>鹿港區</button>
      <label for='sel_school_72'><input type='checkbox' id='sel_school_72' name='sel_school[]' value='72' @if(is_array(old('sel_school')) && in_array(72, old('sel_school'))) checked @endif class='ts_4 z_4_2 ts_32 ts_64'>鹿港國中</label>
      <label for='sel_school_73'><input type='checkbox' id='sel_school_73' name='sel_school[]' value='73' @if(is_array(old('sel_school')) && in_array(73, old('sel_school'))) checked @endif class='ts_4 z_4_2 ts_32 ts_64'>鹿鳴國中</label>
      <label for='sel_school_82'><input type='checkbox' id='sel_school_82' name='sel_school[]' value='82' @if(is_array(old('sel_school')) && in_array(82, old('sel_school'))) checked @endif class='ts_4 z_4_2 ts_32 ts_64'>福興國中</label>
      <label for='sel_school_89'><input type='checkbox' id='sel_school_89' name='sel_school[]' value='89' @if(is_array(old('sel_school')) && in_array(89, old('sel_school'))) checked @endif class='ts_4 z_4_2 ts_32 ts_64'>秀水國中</label>
      <label for='sel_school_247'><input type='checkbox' id='sel_school_247' name='sel_school[]' value='247' @if(is_array(old('sel_school')) && in_array(247, old('sel_school'))) checked @endif class='ts_505 ts_2 z_2_2 ts_4 z_4_2 ts_32 ts_64' onclick="change_another()">鹿江國中(小)</label><br>
      <button  type='button' class='btn btn-info btn-sm my-1' onclick='set_zone(4,3);'>和美區</button>
      <label for='sel_school_244_2' class="text-secondary"><input type='checkbox' id='sel_school_244_2' disabled>和美高中(國中部)</label>
      <label for='sel_school_57'><input type='checkbox' id='sel_school_57' name='sel_school[]' value='57' @if(is_array(old('sel_school')) && in_array(57, old('sel_school'))) checked @endif class='ts_4 z_4_3 ts_32 ts_64'>線西國中</label>
      <label for='sel_school_62'><input type='checkbox' id='sel_school_62' name='sel_school[]' value='62' @if(is_array(old('sel_school')) && in_array(62, old('sel_school'))) checked @endif class='ts_4 z_4_3 ts_32 ts_64'>伸港國中</label>
      <label for='sel_school_53'><input type='checkbox' id='sel_school_53' name='sel_school[]' value='53' @if(is_array(old('sel_school')) && in_array(53, old('sel_school'))) checked @endif class='ts_4 z_4_3 ts_32 ts_64'>和群國中</label><br>
      <button  type='button' class='btn btn-success btn-sm my-1' onclick='set_zone(4,4);'>員林區</button>
      <label for='sel_school_125'><input type='checkbox' id='sel_school_125' name='sel_school[]' value='125' @if(is_array(old('sel_school')) && in_array(125, old('sel_school'))) checked @endif class='ts_4 z_4_4 ts_32 ts_64'>員林國中</label>
      <label for='sel_school_126'><input type='checkbox' id='sel_school_126' name='sel_school[]' value='126' @if(is_array(old('sel_school')) && in_array(126, old('sel_school'))) checked @endif class='ts_4 z_4_4 ts_32 ts_64'>明倫國中</label>
      <label for='sel_school_137'><input type='checkbox' id='sel_school_137' name='sel_school[]' value='137' @if(is_array(old('sel_school')) && in_array(137, old('sel_school'))) checked @endif class='ts_4 z_4_4 ts_32 ts_64'>大村國中</label>
      <label for='sel_school_143'><input type='checkbox' id='sel_school_143' name='sel_school[]' value='143' @if(is_array(old('sel_school')) && in_array(143, old('sel_school'))) checked @endif class='ts_4 z_4_4 ts_32 ts_64'>永靖國中</label>
      <label for='sel_school_127'><input type='checkbox' id='sel_school_127' name='sel_school[]' value='127' @if(is_array(old('sel_school')) && in_array(127, old('sel_school'))) checked @endif class='ts_4 z_4_4 ts_32 ts_64'>大同國中</label><br>
      <button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_zone(4,5);'>田中區</button>
      <label for='sel_school_245_2' class="text-secondary"><input type='checkbox' id='sel_school_245_2' disabled>田中高中(國中部)</label>
      <label for='sel_school_167'><input type='checkbox' id='sel_school_167' name='sel_school[]' value='167' @if(is_array(old('sel_school')) && in_array(167, old('sel_school'))) checked @endif class='ts_4 z_4_5 ts_32 ts_64'>二水國中</label>
      <label for='sel_school_162'><input type='checkbox' id='sel_school_162' name='sel_school[]' value='162' @if(is_array(old('sel_school')) && in_array(162, old('sel_school'))) checked @endif class='ts_4 z_4_5 ts_32 ts_64'>社頭國中</label>
      <label for='sel_school_246'><input type='checkbox' id='sel_school_246' name='sel_school[]' value='246' @if(is_array(old('sel_school')) && in_array(246, old('sel_school'))) checked @endif class='ts_4 z_4_5 ts_32 ts_64'><span class="text-primary">私立文興高中(國中部)</span></label><br>
      <button  type='button' class='btn btn-info btn-sm my-1' onclick='set_zone(4,6);'>溪湖區</button>
      <label for='sel_school_97'><input type='checkbox' id='sel_school_97' name='sel_school[]' value='97' @if(is_array(old('sel_school')) && in_array(97, old('sel_school'))) checked @endif class='ts_4 z_4_6 ts_32 ts_64'>溪湖國中</label>
      <label for='sel_school_107'><input type='checkbox' id='sel_school_107' name='sel_school[]' value='107' @if(is_array(old('sel_school')) && in_array(107, old('sel_school'))) checked @endif class='ts_4 z_4_6 ts_32 ts_64'>埔鹽國中</label>
      <label for='sel_school_115'><input type='checkbox' id='sel_school_115' name='sel_school[]' value='115' @if(is_array(old('sel_school')) && in_array(115, old('sel_school'))) checked @endif class='ts_4 z_4_6 ts_32 ts_64'>埔心國中</label>
      <label for='sel_school_243_2' class="text-secondary"><input type='checkbox' id='sel_school_243_2' disabled>成功高中(國中部)</label><br>
      <button  type='button' class='btn btn-success btn-sm my-1' onclick='set_zone(4,7);'>北斗區</button>
      <label for='sel_school_173'><input type='checkbox' id='sel_school_173' name='sel_school[]' value='173' @if(is_array(old('sel_school')) && in_array(173, old('sel_school'))) checked @endif class='ts_4 z_4_7 ts_32 ts_64'>北斗國中</label>
      <label for='sel_school_179'><input type='checkbox' id='sel_school_179' name='sel_school[]' value='179' @if(is_array(old('sel_school')) && in_array(179, old('sel_school'))) checked @endif class='ts_4 z_4_7 ts_32 ts_64'>田尾國中</label>
      <label for='sel_school_197'><input type='checkbox' id='sel_school_197' name='sel_school[]' value='197' @if(is_array(old('sel_school')) && in_array(197, old('sel_school'))) checked @endif class='ts_4 z_4_7 ts_32 ts_64'>溪州國中</label>
      <label for='sel_school_198'><input type='checkbox' id='sel_school_198' name='sel_school[]' value='198' @if(is_array(old('sel_school')) && in_array(198, old('sel_school'))) checked @endif class='ts_4 z_4_7 ts_32 ts_64'>溪陽國中</label>
      <label for='sel_school_186'><input type='checkbox' id='sel_school_186' name='sel_school[]' value='186' @if(is_array(old('sel_school')) && in_array(186, old('sel_school'))) checked @endif class='ts_4 z_4_7 ts_32 ts_64'>埤頭國中</label><br>
      <button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_zone(4,8);'>二林區</button>
      <label for='sel_school_241_2' class="text-secondary"><input type='checkbox' id='sel_school_241_2' disabled>二林高中(國中部)</label>
      <label for='sel_school_211'><input type='checkbox' id='sel_school_211' name='sel_school[]' value='211' @if(is_array(old('sel_school')) && in_array(211, old('sel_school'))) checked @endif class='ts_4 z_4_8 ts_32 ts_64'>萬興國中</label>
      <label for='sel_school_226'><input type='checkbox' id='sel_school_226' name='sel_school[]' value='226' @if(is_array(old('sel_school')) && in_array(226, old('sel_school'))) checked @endif class='ts_4 z_4_8 ts_32 ts_64'>竹塘國中</label>
      <label for='sel_school_220'><input type='checkbox' id='sel_school_220' name='sel_school[]' value='220' @if(is_array(old('sel_school')) && in_array(220, old('sel_school'))) checked @endif class='ts_4 z_4_8 ts_32 ts_64'>大城國中</label>
      <label for='sel_school_238'><input type='checkbox' id='sel_school_238' name='sel_school[]' value='238' @if(is_array(old('sel_school')) && in_array(238, old('sel_school'))) checked @endif class='ts_4 z_4_8 ts_32 ts_64'>草湖國中</label>
      <label for='sel_school_237'><input type='checkbox' id='sel_school_237' name='sel_school[]' value='237' @if(is_array(old('sel_school')) && in_array(237, old('sel_school'))) checked @endif class='ts_4 z_4_8 ts_32 ts_64'>芳苑國中</label>
      <label for='sel_school_212'><input type='checkbox' id='sel_school_212' name='sel_school[]' value='212' @if(is_array(old('sel_school')) && in_array(212, old('sel_school'))) checked @endif class='ts_526 ts_2 ts_4 z_2_8 z_4_8 ts_16 ts_32 ts_64' onclick="change_another8()">原斗國中(小)</label>
      <label for='sel_school_248'><input type='checkbox' id='sel_school_248' name='sel_school[]' value='248' @if(is_array(old('sel_school')) && in_array(248, old('sel_school'))) checked @endif class='ts_528 ts_2 ts_4 z_2_8 z_4_8 ts_32 ts_64' onclick="change_another9()">民權華德福國中(小)</label>
      <hr>
      <button  type='button' class='btn btn-primary btn-sm' onclick='set_township(2);'>國小全選</button> <button  type='button' class='btn btn-primary btn-sm' onclick='set_township(16);'>附設幼兒園全選</button> <button  type='button' class='btn btn-primary btn-sm' onclick='set_zone(2,1);'>彰化區</button> <button  type='button' class='btn btn-primary btn-sm' onclick='set_zone(2,2);'>鹿港區</button> <button  type='button' class='btn btn-primary btn-sm' onclick='set_zone(2,3);'>和美區</button> <button  type='button' class='btn btn-primary btn-sm' onclick='set_zone(2,4);'>員林區</button> <button  type='button' class='btn btn-primary btn-sm' onclick='set_zone(2,5);'>田中區</button> <button  type='button' class='btn btn-primary btn-sm' onclick='set_zone(2,6);'>溪湖區</button> <button  type='button' class='btn btn-primary btn-sm' onclick='set_zone(2,7);'>北斗區</button> <button  type='button' class='btn btn-primary btn-sm' onclick='set_zone(2,8);'>二林區</button> <button  type='button' class='btn btn-outline-danger btn-sm' onclick='set_none(2)'>國小全不選</button><br>
      <button  type='button' class='btn btn-success btn-sm my-1' onclick='set_township(500);'>500 彰化市全選</button>
      <label for='sel_school_242_2' class="text-secondary"><input type='checkbox' id='sel_school_242_2' disabled>信義國(中)小</label>
      <label for='sel_school_1'><input type='checkbox' id='sel_school_1' name='sel_school[]' value='1' @if(is_array(old('sel_school')) && in_array(1, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_16 ts_32 ts_64'>中山國小(含附幼)</label>
      <label for='sel_school_2'><input type='checkbox' id='sel_school_2' name='sel_school[]' value='2' @if(is_array(old('sel_school')) && in_array(2, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_32 ts_64'>民生國小</label>
      <label for='sel_school_3'><input type='checkbox' id='sel_school_3' name='sel_school[]' value='3' @if(is_array(old('sel_school')) && in_array(3, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_32 ts_64'>平和國小</label>
      <label for='sel_school_4'><input type='checkbox' id='sel_school_4' name='sel_school[]' value='4' @if(is_array(old('sel_school')) && in_array(4, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_16 ts_32 ts_64'>南郭國小(含附幼)</label>
      <label for='sel_school_5'><input type='checkbox' id='sel_school_5' name='sel_school[]' value='5' @if(is_array(old('sel_school')) && in_array(5, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_32 ts_64'>南興國小</label>
      <label for='sel_school_6'><input type='checkbox' id='sel_school_6' name='sel_school[]' value='6' @if(is_array(old('sel_school')) && in_array(6, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_16 ts_32 ts_64'>東芳國小(含附幼)</label>
      <label for='sel_school_7'><input type='checkbox' id='sel_school_7' name='sel_school[]' value='7' @if(is_array(old('sel_school')) && in_array(7, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_32 ts_64'>泰和國小</label>
      <label for='sel_school_8'><input type='checkbox' id='sel_school_8' name='sel_school[]' value='8' @if(is_array(old('sel_school')) && in_array(8, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_32 ts_64'>三民國小</label>
      <label for='sel_school_9'><input type='checkbox' id='sel_school_9' name='sel_school[]' value='9' @if(is_array(old('sel_school')) && in_array(9, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_32 ts_64'>聯興國小</label>
      <label for='sel_school_10'><input type='checkbox' id='sel_school_10' name='sel_school[]' value='10' @if(is_array(old('sel_school')) && in_array(10, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_32 ts_64'>大竹國小</label>
      <label for='sel_school_11'><input type='checkbox' id='sel_school_11' name='sel_school[]' value='11' @if(is_array(old('sel_school')) && in_array(11, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_32 ts_64'>國聖國小</label>
      <label for='sel_school_12'><input type='checkbox' id='sel_school_12' name='sel_school[]' value='12' @if(is_array(old('sel_school')) && in_array(12, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_32 ts_64'>快官國小</label>
      <label for='sel_school_13'><input type='checkbox' id='sel_school_13' name='sel_school[]' value='13' @if(is_array(old('sel_school')) && in_array(13, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_32 ts_64'>石牌國小</label>
      <label for='sel_school_14'><input type='checkbox' id='sel_school_14' name='sel_school[]' value='14' @if(is_array(old('sel_school')) && in_array(14, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_16 ts_32 ts_64'>忠孝國小(含附幼)</label>
      <label for='sel_school_15'><input type='checkbox' id='sel_school_15' name='sel_school[]' value='15' @if(is_array(old('sel_school')) && in_array(15, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_16 ts_32 ts_64'>大成國小(含附幼)</label>
      <label for='sel_school_257'><input type='checkbox' id='sel_school_257' name='sel_school[]' value='257' @if(is_array(old('sel_school')) && in_array(1, old('sel_school'))) checked @endif class='ts_500 ts_2 z_2_1 ts_32 ts_64'>私立正德高中(國小部)</label>
<br><button  type='button' class='btn btn-success btn-sm my-1' onclick='set_township(502);'>502 芬園鄉全選</button>
      <label for='sel_school_30'><input type='checkbox' id='sel_school_30' name='sel_school[]' value='30' @if(is_array(old('sel_school')) && in_array(30, old('sel_school'))) checked @endif class='ts_502 ts_2 z_2_1 ts_16 ts_32 ts_64'>芬園國小(含附幼)</label>
      <label for='sel_school_31'><input type='checkbox' id='sel_school_31' name='sel_school[]' value='31' @if(is_array(old('sel_school')) && in_array(31, old('sel_school'))) checked @endif class='ts_502 ts_2 z_2_1 ts_32 ts_64'>富山國小</label>
      <label for='sel_school_32'><input type='checkbox' id='sel_school_32' name='sel_school[]' value='32' @if(is_array(old('sel_school')) && in_array(32, old('sel_school'))) checked @endif class='ts_502 ts_2 z_2_1 ts_32 ts_64'>寶山國小</label>
      <label for='sel_school_33'><input type='checkbox' id='sel_school_33' name='sel_school[]' value='33' @if(is_array(old('sel_school')) && in_array(33, old('sel_school'))) checked @endif class='ts_502 ts_2 z_2_1 ts_32 ts_64'>同安國小</label>
      <label for='sel_school_35'><input type='checkbox' id='sel_school_35' name='sel_school[]' value='35' @if(is_array(old('sel_school')) && in_array(35, old('sel_school'))) checked @endif class='ts_502 ts_2 z_2_1 ts_32 ts_64'>文德國小</label>
      <label for='sel_school_36'><input type='checkbox' id='sel_school_36' name='sel_school[]' value='36' @if(is_array(old('sel_school')) && in_array(36, old('sel_school'))) checked @endif class='ts_502 ts_2 z_2_1 ts_32 ts_64'>茄荖國小</label>
<br><button  type='button' class='btn btn-success btn-sm my-1' onclick='set_township(503);'>503 花壇鄉全選</button>
      <label for='sel_school_38'><input type='checkbox' id='sel_school_38' name='sel_school[]' value='38' @if(is_array(old('sel_school')) && in_array(38, old('sel_school'))) checked @endif class='ts_503 ts_2 z_2_1 ts_32 ts_64'>花壇國小</label>
      <label for='sel_school_39'><input type='checkbox' id='sel_school_39' name='sel_school[]' value='39' @if(is_array(old('sel_school')) && in_array(39, old('sel_school'))) checked @endif class='ts_503 ts_2 z_2_1 ts_32 ts_64'>文祥國小</label>
      <label for='sel_school_40'><input type='checkbox' id='sel_school_40' name='sel_school[]' value='40' @if(is_array(old('sel_school')) && in_array(40, old('sel_school'))) checked @endif class='ts_503 ts_2 z_2_1 ts_16 ts_32 ts_64'>華南國小(含附幼)</label>
      <label for='sel_school_41'><input type='checkbox' id='sel_school_41' name='sel_school[]' value='41' @if(is_array(old('sel_school')) && in_array(41, old('sel_school'))) checked @endif class='ts_503 ts_2 z_2_1 ts_32 ts_64'>僑愛國小</label>
      <label for='sel_school_42'><input type='checkbox' id='sel_school_42' name='sel_school[]' value='42' @if(is_array(old('sel_school')) && in_array(42, old('sel_school'))) checked @endif class='ts_503 ts_2 z_2_1 ts_32 ts_64'>三春國小</label>
      <label for='sel_school_43'><input type='checkbox' id='sel_school_43' name='sel_school[]' value='43' @if(is_array(old('sel_school')) && in_array(43, old('sel_school'))) checked @endif class='ts_503 ts_2 z_2_1 ts_32 ts_64'>白沙國小</label>
<br><button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_township(504);'>504 秀水鄉全選</button>
      <label for='sel_school_83'><input type='checkbox' id='sel_school_83' name='sel_school[]' value='83' @if(is_array(old('sel_school')) && in_array(83, old('sel_school'))) checked @endif class='ts_504 ts_2 z_2_2 ts_32 ts_64'>秀水國小</label>
      <label for='sel_school_84'><input type='checkbox' id='sel_school_84' name='sel_school[]' value='84' @if(is_array(old('sel_school')) && in_array(84, old('sel_school'))) checked @endif class='ts_504 ts_2 z_2_2 ts_32 ts_64'>馬興國小</label>
      <label for='sel_school_85'><input type='checkbox' id='sel_school_85' name='sel_school[]' value='85' @if(is_array(old('sel_school')) && in_array(85, old('sel_school'))) checked @endif class='ts_504 ts_2 z_2_2 ts_32 ts_64'>華龍國小</label>
      <label for='sel_school_86'><input type='checkbox' id='sel_school_86' name='sel_school[]' value='86' @if(is_array(old('sel_school')) && in_array(86, old('sel_school'))) checked @endif class='ts_504 ts_2 z_2_2 ts_32 ts_64'>明正國小</label>
      <label for='sel_school_87'><input type='checkbox' id='sel_school_87' name='sel_school[]' value='87' @if(is_array(old('sel_school')) && in_array(87, old('sel_school'))) checked @endif class='ts_504 ts_2 z_2_2 ts_32 ts_64'>陝西國小</label>
      <label for='sel_school_88'><input type='checkbox' id='sel_school_88' name='sel_school[]' value='88' @if(is_array(old('sel_school')) && in_array(88, old('sel_school'))) checked @endif class='ts_504 ts_2 z_2_2 ts_32 ts_64'>育民國小</label>
<br><button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_township(505);'>505 鹿港鎮全選</button>
      <label for='sel_school_63'><input type='checkbox' id='sel_school_63' name='sel_school[]' value='63' @if(is_array(old('sel_school')) && in_array(63, old('sel_school'))) checked @endif class='ts_505 ts_2 z_2_2 ts_16 ts_32 ts_64'>鹿港國小(含附幼)</label>
      <label for='sel_school_64'><input type='checkbox' id='sel_school_64' name='sel_school[]' value='64' @if(is_array(old('sel_school')) && in_array(64, old('sel_school'))) checked @endif class='ts_505 ts_2 z_2_2 ts_16 ts_32 ts_64'>文開國小(含附幼)</label>
      <label for='sel_school_65'><input type='checkbox' id='sel_school_65' name='sel_school[]' value='65' @if(is_array(old('sel_school')) && in_array(65, old('sel_school'))) checked @endif class='ts_505 ts_2 z_2_2 ts_16 ts_32 ts_64'>洛津國小(含附幼)</label>
      <label for='sel_school_66'><input type='checkbox' id='sel_school_66' name='sel_school[]' value='66' @if(is_array(old('sel_school')) && in_array(66, old('sel_school'))) checked @endif class='ts_505 ts_2 z_2_2 ts_16 ts_32 ts_64'>海埔國小(含附幼)</label>
      <label for='sel_school_67'><input type='checkbox' id='sel_school_67' name='sel_school[]' value='67' @if(is_array(old('sel_school')) && in_array(67, old('sel_school'))) checked @endif class='ts_505 ts_2 z_2_2 ts_32 ts_64'>新興國小</label>
      <label for='sel_school_68'><input type='checkbox' id='sel_school_68' name='sel_school[]' value='68' @if(is_array(old('sel_school')) && in_array(68, old('sel_school'))) checked @endif class='ts_505 ts_2 z_2_2 ts_32 ts_64'>草港國小</label>
      <label for='sel_school_69'><input type='checkbox' id='sel_school_69' name='sel_school[]' value='69' @if(is_array(old('sel_school')) && in_array(69, old('sel_school'))) checked @endif class='ts_505 ts_2 z_2_2 ts_16 ts_32 ts_64'>頂番國小(含附幼)</label>
      <label for='sel_school_70'><input type='checkbox' id='sel_school_70' name='sel_school[]' value='70' @if(is_array(old('sel_school')) && in_array(70, old('sel_school'))) checked @endif class='ts_505 ts_2 z_2_2 ts_16 ts_32 ts_64'>東興國小(含附幼)</label>
      <label for='sel_school_71'><input type='checkbox' id='sel_school_71' name='sel_school[]' value='71' @if(is_array(old('sel_school')) && in_array(71, old('sel_school'))) checked @endif class='ts_505 ts_2 z_2_2 ts_16 ts_32 ts_64'>鹿東國小(含附幼)</label>
      <label for='sel_school_247_2' class="text-secondary"><input type='checkbox' id='sel_school_247_2' disabled>鹿江國(中)小</label>

<br><button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_township(506);'>506 福興鄉全選</button>
      <label for='sel_school_75'><input type='checkbox' id='sel_school_75' name='sel_school[]' value='75' @if(is_array(old('sel_school')) && in_array(75, old('sel_school'))) checked @endif class='ts_506 ts_2 z_2_2 ts_16 ts_32 ts_64'>管嶼國小(含附幼)</label>
      <label for='sel_school_76'><input type='checkbox' id='sel_school_76' name='sel_school[]' value='76' @if(is_array(old('sel_school')) && in_array(76, old('sel_school'))) checked @endif class='ts_506 ts_2 z_2_2 ts_32 ts_64'>文昌國小</label>
      <label for='sel_school_77'><input type='checkbox' id='sel_school_77' name='sel_school[]' value='77' @if(is_array(old('sel_school')) && in_array(77, old('sel_school'))) checked @endif class='ts_506 ts_2 z_2_2 ts_32 ts_64'>西勢國小</label>
      <label for='sel_school_78'><input type='checkbox' id='sel_school_78' name='sel_school[]' value='78' @if(is_array(old('sel_school')) && in_array(78, old('sel_school'))) checked @endif class='ts_506 ts_2 z_2_2 ts_32 ts_64'>大興國小</label>
      <label for='sel_school_79'><input type='checkbox' id='sel_school_79' name='sel_school[]' value='79' @if(is_array(old('sel_school')) && in_array(79, old('sel_school'))) checked @endif class='ts_506 ts_2 z_2_2 ts_32 ts_64'>永豐國小</label>
      <label for='sel_school_80'><input type='checkbox' id='sel_school_80' name='sel_school[]' value='80' @if(is_array(old('sel_school')) && in_array(80, old('sel_school'))) checked @endif class='ts_506 ts_2 z_2_2 ts_32 ts_64'>日新國小</label>
      <label for='sel_school_81'><input type='checkbox' id='sel_school_81' name='sel_school[]' value='81' @if(is_array(old('sel_school')) && in_array(81, old('sel_school'))) checked @endif class='ts_506 ts_2 z_2_2 ts_16 ts_32 ts_64'>育新國小(含附幼)</label>
<br><button  type='button' class='btn btn-info btn-sm my-1' onclick='set_township(507);'>507 線西鄉全選</button>
      <label for='sel_school_55'><input type='checkbox' id='sel_school_55' name='sel_school[]' value='55' @if(is_array(old('sel_school')) && in_array(55, old('sel_school'))) checked @endif class='ts_507 ts_2 z_2_3 ts_16 ts_32 ts_64'>線西國小(含附幼)</label>
      <label for='sel_school_56'><input type='checkbox' id='sel_school_56' name='sel_school[]' value='56' @if(is_array(old('sel_school')) && in_array(56, old('sel_school'))) checked @endif class='ts_507 ts_2 z_2_3 ts_32 ts_64'>曉陽國小</label>
<br><button  type='button' class='btn btn-info btn-sm my-1' onclick='set_township(508);'>508 和美鎮全選</button>
      <label for='sel_school_45'><input type='checkbox' id='sel_school_45' name='sel_school[]' value='45' @if(is_array(old('sel_school')) && in_array(45, old('sel_school'))) checked @endif class='ts_508 ts_2 z_2_3 ts_16 ts_32 ts_64'>和美國小(含附幼)</label>
      <label for='sel_school_46'><input type='checkbox' id='sel_school_46' name='sel_school[]' value='46' @if(is_array(old('sel_school')) && in_array(46, old('sel_school'))) checked @endif class='ts_508 ts_2 z_2_3 ts_32 ts_64'>和東國小</label>
      <label for='sel_school_47'><input type='checkbox' id='sel_school_47' name='sel_school[]' value='47' @if(is_array(old('sel_school')) && in_array(47, old('sel_school'))) checked @endif class='ts_508 ts_2 z_2_3 ts_16 ts_32 ts_64'>大嘉國小(含附幼)</label>
      <label for='sel_school_48'><input type='checkbox' id='sel_school_48' name='sel_school[]' value='48' @if(is_array(old('sel_school')) && in_array(48, old('sel_school'))) checked @endif class='ts_508 ts_2 z_2_3 ts_32 ts_64'>大榮國小</label>
      <label for='sel_school_49'><input type='checkbox' id='sel_school_49' name='sel_school[]' value='49' @if(is_array(old('sel_school')) && in_array(49, old('sel_school'))) checked @endif class='ts_508 ts_2 z_2_3 ts_32 ts_64'>新庄國小</label>
      <label for='sel_school_50'><input type='checkbox' id='sel_school_50' name='sel_school[]' value='50' @if(is_array(old('sel_school')) && in_array(50, old('sel_school'))) checked @endif class='ts_508 ts_2 z_2_3 ts_16 ts_32 ts_64'>培英國小(含附幼)</label>
      <label for='sel_school_51'><input type='checkbox' id='sel_school_51' name='sel_school[]' value='51' @if(is_array(old('sel_school')) && in_array(51, old('sel_school'))) checked @endif class='ts_508 ts_2 z_2_3 ts_16 ts_32 ts_64'>和仁國小(含附幼)</label>
<br><button  type='button' class='btn btn-info btn-sm my-1' onclick='set_township(509);'>509 伸港鄉全選</button>
      <label for='sel_school_58'><input type='checkbox' id='sel_school_58' name='sel_school[]' value='58' @if(is_array(old('sel_school')) && in_array(58, old('sel_school'))) checked @endif class='ts_509 ts_2 z_2_3 ts_32 ts_64'>新港國小</label>
      <label for='sel_school_59'><input type='checkbox' id='sel_school_59' name='sel_school[]' value='59' @if(is_array(old('sel_school')) && in_array(59, old('sel_school'))) checked @endif class='ts_509 ts_2 z_2_3 ts_32 ts_64'>伸東國小</label>
      <label for='sel_school_60'><input type='checkbox' id='sel_school_60' name='sel_school[]' value='60' @if(is_array(old('sel_school')) && in_array(60, old('sel_school'))) checked @endif class='ts_509 ts_2 z_2_3 ts_32 ts_64'>伸仁國小</label>
      <label for='sel_school_61'><input type='checkbox' id='sel_school_61' name='sel_school[]' value='61' @if(is_array(old('sel_school')) && in_array(61, old('sel_school'))) checked @endif class='ts_509 ts_2 z_2_3 ts_32 ts_64'>大同國小</label>
<br><button  type='button' class='btn btn-success btn-sm my-1' onclick='set_township(510);'>510 員林市全選</button>
      <label for='sel_school_116'><input type='checkbox' id='sel_school_116' name='sel_school[]' value='116' @if(is_array(old('sel_school')) && in_array(116, old('sel_school'))) checked @endif class='ts_510 ts_2 z_2_4 ts_32 ts_64'>員林國小</label>
      <label for='sel_school_117'><input type='checkbox' id='sel_school_117' name='sel_school[]' value='117' @if(is_array(old('sel_school')) && in_array(117, old('sel_school'))) checked @endif class='ts_510 ts_2 z_2_4 ts_32 ts_64'>育英國小</label>
      <label for='sel_school_118'><input type='checkbox' id='sel_school_118' name='sel_school[]' value='118' @if(is_array(old('sel_school')) && in_array(118, old('sel_school'))) checked @endif class='ts_510 ts_2 z_2_4 ts_16 ts_32 ts_64'>靜修國小(含附幼)</label>
      <label for='sel_school_119'><input type='checkbox' id='sel_school_119' name='sel_school[]' value='119' @if(is_array(old('sel_school')) && in_array(119, old('sel_school'))) checked @endif class='ts_510 ts_2 z_2_4 ts_16 ts_32 ts_64'>僑信國小(含附幼)</label>
      <label for='sel_school_120'><input type='checkbox' id='sel_school_120' name='sel_school[]' value='120' @if(is_array(old('sel_school')) && in_array(120, old('sel_school'))) checked @endif class='ts_510 ts_2 z_2_4 ts_16 ts_32 ts_64'>員東國小(含附幼)</label>
      <label for='sel_school_122'><input type='checkbox' id='sel_school_122' name='sel_school[]' value='122' @if(is_array(old('sel_school')) && in_array(122, old('sel_school'))) checked @endif class='ts_510 ts_2 z_2_4 ts_16 ts_32 ts_64'>饒明國小(含附幼)</label>
      <label for='sel_school_123'><input type='checkbox' id='sel_school_123' name='sel_school[]' value='123' @if(is_array(old('sel_school')) && in_array(123, old('sel_school'))) checked @endif class='ts_510 ts_2 z_2_4 ts_32 ts_64'>東山國小</label>
      <label for='sel_school_121'><input type='checkbox' id='sel_school_121' name='sel_school[]' value='121' @if(is_array(old('sel_school')) && in_array(121, old('sel_school'))) checked @endif class='ts_510 ts_2 z_2_4 ts_16 ts_32 ts_64'>青山國小(含附幼)</label>
      <label for='sel_school_124'><input type='checkbox' id='sel_school_124' name='sel_school[]' value='124' @if(is_array(old('sel_school')) && in_array(124, old('sel_school'))) checked @endif class='ts_510 ts_2 z_2_4 ts_32 ts_64'>明湖國小</label>
<br><button  type='button' class='btn btn-success btn-sm my-1' onclick='set_township(512);'>512 永靖鄉全選</button>
      <label for='sel_school_138'><input type='checkbox' id='sel_school_138' name='sel_school[]' value='138' @if(is_array(old('sel_school')) && in_array(138, old('sel_school'))) checked @endif class='ts_512 ts_2 z_2_4 ts_16 ts_32 ts_64'>永靖國小(含附幼)</label>
      <label for='sel_school_139'><input type='checkbox' id='sel_school_139' name='sel_school[]' value='139' @if(is_array(old('sel_school')) && in_array(139, old('sel_school'))) checked @endif class='ts_512 ts_2 z_2_4 ts_32 ts_64'>福德國小</label>
      <label for='sel_school_140'><input type='checkbox' id='sel_school_140' name='sel_school[]' value='140' @if(is_array(old('sel_school')) && in_array(140, old('sel_school'))) checked @endif class='ts_512 ts_2 z_2_4 ts_32 ts_64'>永興國小</label>
      <label for='sel_school_141'><input type='checkbox' id='sel_school_141' name='sel_school[]' value='141' @if(is_array(old('sel_school')) && in_array(141, old('sel_school'))) checked @endif class='ts_512 ts_2 z_2_4 ts_32 ts_64'>福興國小</label>
      <label for='sel_school_142'><input type='checkbox' id='sel_school_142' name='sel_school[]' value='142' @if(is_array(old('sel_school')) && in_array(142, old('sel_school'))) checked @endif class='ts_512 ts_2 z_2_4 ts_32 ts_64'>德興國小</label>
<br><button  type='button' class='btn btn-success btn-sm my-1' onclick='set_township(515);'>515 大村鄉全選</button>
      <label for='sel_school_133'><input type='checkbox' id='sel_school_133' name='sel_school[]' value='133' @if(is_array(old('sel_school')) && in_array(133, old('sel_school'))) checked @endif class='ts_515 ts_2 z_2_4 ts_32 ts_64'>大村國小</label>
      <label for='sel_school_134'><input type='checkbox' id='sel_school_134' name='sel_school[]' value='134' @if(is_array(old('sel_school')) && in_array(134, old('sel_school'))) checked @endif class='ts_515 ts_2 z_2_4 ts_32 ts_64'>大西國小</label>
      <label for='sel_school_135'><input type='checkbox' id='sel_school_135' name='sel_school[]' value='135' @if(is_array(old('sel_school')) && in_array(135, old('sel_school'))) checked @endif class='ts_515 ts_2 z_2_4 ts_32 ts_64'>村上國小</label>
      <label for='sel_school_136'><input type='checkbox' id='sel_school_136' name='sel_school[]' value='136' @if(is_array(old('sel_school')) && in_array(136, old('sel_school'))) checked @endif class='ts_515 ts_2 z_2_4 ts_32 ts_64'>村東國小</label>
<br><button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_township(511);'>511 社頭鄉全選</button>
      <label for='sel_school_155'><input type='checkbox' id='sel_school_155' name='sel_school[]' value='155' @if(is_array(old('sel_school')) && in_array(155, old('sel_school'))) checked @endif class='ts_511 ts_2 z_2_5 ts_16 ts_32 ts_64'>社頭國小(含附幼)</label>
      <label for='sel_school_156'><input type='checkbox' id='sel_school_156' name='sel_school[]' value='156' @if(is_array(old('sel_school')) && in_array(156, old('sel_school'))) checked @endif class='ts_511 ts_2 z_2_5 ts_16 ts_32 ts_64'>橋頭國小(含附幼)</label>
      <label for='sel_school_157'><input type='checkbox' id='sel_school_157' name='sel_school[]' value='157' @if(is_array(old('sel_school')) && in_array(157, old('sel_school'))) checked @endif class='ts_511 ts_2 z_2_5 ts_32 ts_64'>朝興國小</label>
      <label for='sel_school_158'><input type='checkbox' id='sel_school_158' name='sel_school[]' value='158' @if(is_array(old('sel_school')) && in_array(158, old('sel_school'))) checked @endif class='ts_511 ts_2 z_2_5 ts_32 ts_64'>清水國小</label>
      <label for='sel_school_159'><input type='checkbox' id='sel_school_159' name='sel_school[]' value='159' @if(is_array(old('sel_school')) && in_array(159, old('sel_school'))) checked @endif class='ts_511 ts_2 z_2_5 ts_32 ts_64'>湳雅國小</label>
      <label for='sel_school_161'><input type='checkbox' id='sel_school_161' name='sel_school[]' value='161' @if(is_array(old('sel_school')) && in_array(161, old('sel_school'))) checked @endif class='ts_511 ts_2 z_2_5 ts_32 ts_64'>舊社國小</label>
      <label for='sel_school_160'><input type='checkbox' id='sel_school_160' name='sel_school[]' value='160' @if(is_array(old('sel_school')) && in_array(160, old('sel_school'))) checked @endif class='ts_511 ts_2 z_2_5 ts_16 ts_32 ts_64'>崙雅國小(含附幼)</label>
<br><button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_township(520);'>520 田中鎮全選</button>
      <label for='sel_school_145'><input type='checkbox' id='sel_school_145' name='sel_school[]' value='145' @if(is_array(old('sel_school')) && in_array(145, old('sel_school'))) checked @endif class='ts_520 ts_2 z_2_5 ts_16 ts_32 ts_64'>田中國小(含附幼)</label>
      <label for='sel_school_146'><input type='checkbox' id='sel_school_146' name='sel_school[]' value='146' @if(is_array(old('sel_school')) && in_array(146, old('sel_school'))) checked @endif class='ts_520 ts_2 z_2_5 ts_32 ts_64'>三潭國小</label>
      <label for='sel_school_147'><input type='checkbox' id='sel_school_147' name='sel_school[]' value='147' @if(is_array(old('sel_school')) && in_array(147, old('sel_school'))) checked @endif class='ts_520 ts_2 z_2_5 ts_32 ts_64'>大安國小</label>
      <label for='sel_school_148'><input type='checkbox' id='sel_school_148' name='sel_school[]' value='148' @if(is_array(old('sel_school')) && in_array(148, old('sel_school'))) checked @endif class='ts_520 ts_2 z_2_5 ts_32 ts_64'>內安國小</label>
      <label for='sel_school_149'><input type='checkbox' id='sel_school_149' name='sel_school[]' value='149' @if(is_array(old('sel_school')) && in_array(149, old('sel_school'))) checked @endif class='ts_520 ts_2 z_2_5 ts_32 ts_64'>東和國小</label>
      <label for='sel_school_150'><input type='checkbox' id='sel_school_150' name='sel_school[]' value='150' @if(is_array(old('sel_school')) && in_array(150, old('sel_school'))) checked @endif class='ts_520 ts_2 z_2_5 ts_32 ts_64'>明禮國小</label>
      <label for='sel_school_151'><input type='checkbox' id='sel_school_151' name='sel_school[]' value='151' @if(is_array(old('sel_school')) && in_array(151, old('sel_school'))) checked @endif class='ts_520 ts_2 z_2_5 ts_32 ts_64'>新民國小</label>
<br><button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_township(530);'>530 二水鄉全選</button>
      <label for='sel_school_164'><input type='checkbox' id='sel_school_164' name='sel_school[]' value='164' @if(is_array(old('sel_school')) && in_array(164, old('sel_school'))) checked @endif class='ts_530 ts_2 z_2_5 ts_16 ts_32 ts_64'>二水國小(含附幼)</label>
      <label for='sel_school_165'><input type='checkbox' id='sel_school_165' name='sel_school[]' value='165' @if(is_array(old('sel_school')) && in_array(165, old('sel_school'))) checked @endif class='ts_530 ts_2 z_2_5 ts_32 ts_64'>復興國小</label>
      <label for='sel_school_166'><input type='checkbox' id='sel_school_166' name='sel_school[]' value='166' @if(is_array(old('sel_school')) && in_array(166, old('sel_school'))) checked @endif class='ts_530 ts_2 z_2_5 ts_32 ts_64'>源泉國小</label>
<br><button  type='button' class='btn btn-info btn-sm my-1' onclick='set_township(513);'>513 埔心鄉全選</button>
      <label for='sel_school_108'><input type='checkbox' id='sel_school_108' name='sel_school[]' value='108' @if(is_array(old('sel_school')) && in_array(108, old('sel_school'))) checked @endif class='ts_513 ts_2 z_2_6 ts_32 ts_64'>埔心國小</label>
      <label for='sel_school_109'><input type='checkbox' id='sel_school_109' name='sel_school[]' value='109' @if(is_array(old('sel_school')) && in_array(109, old('sel_school'))) checked @endif class='ts_513 ts_2 z_2_6 ts_32 ts_64'>太平國小</label>
      <label for='sel_school_110'><input type='checkbox' id='sel_school_110' name='sel_school[]' value='110' @if(is_array(old('sel_school')) && in_array(110, old('sel_school'))) checked @endif class='ts_513 ts_2 z_2_6 ts_32 ts_64'>舊館國小</label>
      <label for='sel_school_111'><input type='checkbox' id='sel_school_111' name='sel_school[]' value='111' @if(is_array(old('sel_school')) && in_array(111, old('sel_school'))) checked @endif class='ts_513 ts_2 z_2_6 ts_32 ts_64'>羅厝國小</label>
      <label for='sel_school_112'><input type='checkbox' id='sel_school_112' name='sel_school[]' value='112' @if(is_array(old('sel_school')) && in_array(112, old('sel_school'))) checked @endif class='ts_513 ts_2 z_2_6 ts_32 ts_64'>鳳霞國小</label>
      <label for='sel_school_113'><input type='checkbox' id='sel_school_113' name='sel_school[]' value='113' @if(is_array(old('sel_school')) && in_array(113, old('sel_school'))) checked @endif class='ts_513 ts_2 z_2_6 ts_32 ts_64'>梧鳳國小</label>
      <label for='sel_school_114'><input type='checkbox' id='sel_school_114' name='sel_school[]' value='114' @if(is_array(old('sel_school')) && in_array(114, old('sel_school'))) checked @endif class='ts_513 ts_2 z_2_6 ts_32 ts_64'>明聖國小</label>
<br><button  type='button' class='btn btn-info btn-sm my-1' onclick='set_township(514);'>514 溪湖鎮全選</button>
      <label for='sel_school_91'><input type='checkbox' id='sel_school_91' name='sel_school[]' value='91' @if(is_array(old('sel_school')) && in_array(91, old('sel_school'))) checked @endif class='ts_514 ts_2 z_2_6 ts_16 ts_32 ts_64'>溪湖國小(含附幼)</label>
      <label for='sel_school_92'><input type='checkbox' id='sel_school_92' name='sel_school[]' value='92' @if(is_array(old('sel_school')) && in_array(92, old('sel_school'))) checked @endif class='ts_514 ts_2 z_2_6 ts_32 ts_64'>東溪國小</label>
      <label for='sel_school_93'><input type='checkbox' id='sel_school_93' name='sel_school[]' value='93' @if(is_array(old('sel_school')) && in_array(93, old('sel_school'))) checked @endif class='ts_514 ts_2 z_2_6 ts_32 ts_64'>湖西國小</label>
      <label for='sel_school_94'><input type='checkbox' id='sel_school_94' name='sel_school[]' value='94' @if(is_array(old('sel_school')) && in_array(94, old('sel_school'))) checked @endif class='ts_514 ts_2 z_2_6 ts_32 ts_64 ts_16'>湖東國小(含附幼)</label>
      <label for='sel_school_95'><input type='checkbox' id='sel_school_95' name='sel_school[]' value='95' @if(is_array(old('sel_school')) && in_array(95, old('sel_school'))) checked @endif class='ts_514 ts_2 z_2_6 ts_32 ts_64'>湖南國小</label>
      <label for='sel_school_96'><input type='checkbox' id='sel_school_96' name='sel_school[]' value='96' @if(is_array(old('sel_school')) && in_array(96, old('sel_school'))) checked @endif class='ts_514 ts_2 z_2_6 ts_32 ts_64'>媽厝國小</label>
      <label for='sel_school_239'><input type='checkbox' id='sel_school_239' name='sel_school[]' value='239' @if(is_array(old('sel_school')) && in_array(239, old('sel_school'))) checked @endif class='ts_514 ts_2 z_2_6 ts_16 ts_32 ts_64'>湖北國小(含附幼)</label>
<br><button  type='button' class='btn btn-info btn-sm my-1' onclick='set_township(516);'>516 埔鹽鄉全選</button>
      <label for='sel_school_100'><input type='checkbox' id='sel_school_100' name='sel_school[]' value='100' @if(is_array(old('sel_school')) && in_array(100, old('sel_school'))) checked @endif class='ts_516 ts_2 z_2_6 ts_32 ts_64'>埔鹽國小</label>
      <label for='sel_school_101'><input type='checkbox' id='sel_school_101' name='sel_school[]' value='101' @if(is_array(old('sel_school')) && in_array(101, old('sel_school'))) checked @endif class='ts_516 ts_2 z_2_6 ts_32 ts_64'>大園國小</label>
      <label for='sel_school_102'><input type='checkbox' id='sel_school_102' name='sel_school[]' value='102' @if(is_array(old('sel_school')) && in_array(102, old('sel_school'))) checked @endif class='ts_516 ts_2 z_2_6 ts_32 ts_64'>南港國小</label>
      <label for='sel_school_103'><input type='checkbox' id='sel_school_103' name='sel_school[]' value='103' @if(is_array(old('sel_school')) && in_array(103, old('sel_school'))) checked @endif class='ts_516 ts_2 z_2_6 ts_16 ts_32 ts_64'>好修國小(含附幼)</label>
      <label for='sel_school_104'><input type='checkbox' id='sel_school_104' name='sel_school[]' value='104' @if(is_array(old('sel_school')) && in_array(104, old('sel_school'))) checked @endif class='ts_516 ts_2 z_2_6 ts_32 ts_64'>永樂國小</label>
      <label for='sel_school_105'><input type='checkbox' id='sel_school_105' name='sel_school[]' value='105' @if(is_array(old('sel_school')) && in_array(105, old('sel_school'))) checked @endif class='ts_516 ts_2 z_2_6 ts_32 ts_64'>新水國小</label>
      <label for='sel_school_106'><input type='checkbox' id='sel_school_106' name='sel_school[]' value='106' @if(is_array(old('sel_school')) && in_array(106, old('sel_school'))) checked @endif class='ts_516 ts_2 z_2_6 ts_32 ts_64'>天盛國小</label>
<br><button  type='button' class='btn btn-success btn-sm my-1' onclick='set_township(521);'>521 北斗鎮全選</button>
      <label for='sel_school_168'><input type='checkbox' id='sel_school_168' name='sel_school[]' value='168' @if(is_array(old('sel_school')) && in_array(168, old('sel_school'))) checked @endif class='ts_521 ts_2 z_2_7 ts_16 ts_32 ts_64'>北斗國小(含附幼)</label>
      <label for='sel_school_169'><input type='checkbox' id='sel_school_169' name='sel_school[]' value='169' @if(is_array(old('sel_school')) && in_array(169, old('sel_school'))) checked @endif class='ts_521 ts_2 z_2_7 ts_32 ts_64'>萬來國小</label>
      <label for='sel_school_170'><input type='checkbox' id='sel_school_170' name='sel_school[]' value='170' @if(is_array(old('sel_school')) && in_array(170, old('sel_school'))) checked @endif class='ts_521 ts_2 z_2_7 ts_32 ts_64'>螺青國小</label>
      <label for='sel_school_171'><input type='checkbox' id='sel_school_171' name='sel_school[]' value='171' @if(is_array(old('sel_school')) && in_array(171, old('sel_school'))) checked @endif class='ts_521 ts_2 z_2_7 ts_32 ts_64'>大新國小</label>
      <label for='sel_school_172'><input type='checkbox' id='sel_school_172' name='sel_school[]' value='172' @if(is_array(old('sel_school')) && in_array(172, old('sel_school'))) checked @endif class='ts_521 ts_2 z_2_7 ts_32 ts_64'>螺陽國小</label>
<br><button  type='button' class='btn btn-success btn-sm my-1' onclick='set_township(522);'>522 田尾鄉全選</button>
      <label for='sel_school_175'><input type='checkbox' id='sel_school_175' name='sel_school[]' value='175' @if(is_array(old('sel_school')) && in_array(175, old('sel_school'))) checked @endif class='ts_522 ts_2 z_2_7 ts_16 ts_32 ts_64'>田尾國小(含附幼)</label>
      <label for='sel_school_176'><input type='checkbox' id='sel_school_176' name='sel_school[]' value='176' @if(is_array(old('sel_school')) && in_array(176, old('sel_school'))) checked @endif class='ts_522 ts_2 z_2_7 ts_32 ts_64'>南鎮國小</label>
      <label for='sel_school_177'><input type='checkbox' id='sel_school_177' name='sel_school[]' value='177' @if(is_array(old('sel_school')) && in_array(177, old('sel_school'))) checked @endif class='ts_522 ts_2 z_2_7 ts_32 ts_64'>陸豐國小</label>
      <label for='sel_school_178'><input type='checkbox' id='sel_school_178' name='sel_school[]' value='178' @if(is_array(old('sel_school')) && in_array(178, old('sel_school'))) checked @endif class='ts_522 ts_2 z_2_7 ts_32 ts_64'>仁豐國小</label>
<br><button  type='button' class='btn btn-success btn-sm my-1' onclick='set_township(523);'>523 埤頭鄉全選</button>
      <label for='sel_school_180'><input type='checkbox' id='sel_school_180' name='sel_school[]' value='180' @if(is_array(old('sel_school')) && in_array(180, old('sel_school'))) checked @endif class='ts_523 ts_2 z_2_7 ts_32 ts_64'>埤頭國小</label>
      <label for='sel_school_181'><input type='checkbox' id='sel_school_181' name='sel_school[]' value='181' @if(is_array(old('sel_school')) && in_array(181, old('sel_school'))) checked @endif class='ts_523 ts_2 z_2_7 ts_16 ts_32 ts_64'>合興國小(含附幼)</label>
      <label for='sel_school_182'><input type='checkbox' id='sel_school_182' name='sel_school[]' value='182' @if(is_array(old('sel_school')) && in_array(182, old('sel_school'))) checked @endif class='ts_523 ts_2 z_2_7 ts_32 ts_64'>豐崙國小</label>
      <label for='sel_school_183'><input type='checkbox' id='sel_school_183' name='sel_school[]' value='183' @if(is_array(old('sel_school')) && in_array(183, old('sel_school'))) checked @endif class='ts_523 ts_2 z_2_7 ts_32 ts_64'>芙朝國小</label>
      <label for='sel_school_184'><input type='checkbox' id='sel_school_184' name='sel_school[]' value='184' @if(is_array(old('sel_school')) && in_array(184, old('sel_school'))) checked @endif class='ts_523 ts_2 z_2_7 ts_32 ts_64'>中和國小</label>
      <label for='sel_school_185'><input type='checkbox' id='sel_school_185' name='sel_school[]' value='185' @if(is_array(old('sel_school')) && in_array(185, old('sel_school'))) checked @endif class='ts_523 ts_2 z_2_7 ts_32 ts_64'>大湖國小</label>
<br><button  type='button' class='btn btn-success btn-sm my-1' onclick='set_township(524);'>524 溪州鄉全選</button>
      <label for='sel_school_187'><input type='checkbox' id='sel_school_187' name='sel_school[]' value='187' @if(is_array(old('sel_school')) && in_array(187, old('sel_school'))) checked @endif class='ts_524 ts_2 z_2_7 ts_16 ts_32 ts_64'>溪州國小(含附幼)</label>
      <label for='sel_school_188'><input type='checkbox' id='sel_school_188' name='sel_school[]' value='188' @if(is_array(old('sel_school')) && in_array(188, old('sel_school'))) checked @endif class='ts_524 ts_2 z_2_7 ts_32 ts_64'>僑義國小</label>
      <label for='sel_school_189'><input type='checkbox' id='sel_school_189' name='sel_school[]' value='189' @if(is_array(old('sel_school')) && in_array(189, old('sel_school'))) checked @endif class='ts_524 ts_2 z_2_7 ts_32 ts_64'>三條國小</label>
      <label for='sel_school_190'><input type='checkbox' id='sel_school_190' name='sel_school[]' value='190' @if(is_array(old('sel_school')) && in_array(190, old('sel_school'))) checked @endif class='ts_524 ts_2 z_2_7 ts_32 ts_64'>水尾國小</label>
      <label for='sel_school_191'><input type='checkbox' id='sel_school_191' name='sel_school[]' value='191' @if(is_array(old('sel_school')) && in_array(191, old('sel_school'))) checked @endif class='ts_524 ts_2 z_2_7 ts_32 ts_64'>潮洋國小</label>
      <label for='sel_school_192'><input type='checkbox' id='sel_school_192' name='sel_school[]' value='192' @if(is_array(old('sel_school')) && in_array(192, old('sel_school'))) checked @endif class='ts_524 ts_2 z_2_7 ts_32 ts_64'>成功國小</label>
      <label for='sel_school_194'><input type='checkbox' id='sel_school_194' name='sel_school[]' value='194' @if(is_array(old('sel_school')) && in_array(194, old('sel_school'))) checked @endif class='ts_524 ts_2 z_2_7 ts_32 ts_64'>圳寮國小</label>
      <label for='sel_school_195'><input type='checkbox' id='sel_school_195' name='sel_school[]' value='195' @if(is_array(old('sel_school')) && in_array(195, old('sel_school'))) checked @endif class='ts_524 ts_2 z_2_7 ts_32 ts_64'>大莊國小</label>
      <label for='sel_school_196'><input type='checkbox' id='sel_school_196' name='sel_school[]' value='196' @if(is_array(old('sel_school')) && in_array(196, old('sel_school'))) checked @endif class='ts_524 ts_2 z_2_7 ts_32 ts_64'>南州國小</label>
<br><button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_township(525);'>525 竹塘鄉全選</button>
      <label for='sel_school_221'><input type='checkbox' id='sel_school_221' name='sel_school[]' value='221' @if(is_array(old('sel_school')) && in_array(221, old('sel_school'))) checked @endif class='ts_525 ts_2 z_2_8 ts_16 ts_32 ts_64'>竹塘國小(含附幼)</label>
      <label for='sel_school_222'><input type='checkbox' id='sel_school_222' name='sel_school[]' value='222' @if(is_array(old('sel_school')) && in_array(222, old('sel_school'))) checked @endif class='ts_525 ts_2 z_2_8 ts_32 ts_64'>田頭國小</label>
      <!--
      <label for='sel_school_223'><input type='checkbox' id='sel_school_223' name='sel_school[]' value='223' @if(is_array(old('sel_school')) && in_array(223, old('sel_school'))) checked @endif class='ts_525 ts_2 z_2_8 ts_32 ts_64'>民靖國小</label>
      -->
      <label for='sel_school_224'><input type='checkbox' id='sel_school_224' name='sel_school[]' value='224' @if(is_array(old('sel_school')) && in_array(224, old('sel_school'))) checked @endif class='ts_525 ts_2 z_2_8 ts_32 ts_64'>長安國小</label>
      <label for='sel_school_225'><input type='checkbox' id='sel_school_225' name='sel_school[]' value='225' @if(is_array(old('sel_school')) && in_array(225, old('sel_school'))) checked @endif class='ts_525 ts_2 z_2_8 ts_32 ts_64'>土庫國小</label>
<br><button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_township(526);'>526 二林鎮全選</button>
      <label for='sel_school_199'><input type='checkbox' id='sel_school_199' name='sel_school[]' value='199' @if(is_array(old('sel_school')) && in_array(199, old('sel_school'))) checked @endif class='ts_526 ts_2 z_2_8 ts_32 ts_64'>二林國小</label>
      <label for='sel_school_200'><input type='checkbox' id='sel_school_200' name='sel_school[]' value='200' @if(is_array(old('sel_school')) && in_array(200, old('sel_school'))) checked @endif class='ts_526 ts_2 z_2_8 ts_16 ts_32 ts_64'>興華國小(含附幼)</label>
      <label for='sel_school_201'><input type='checkbox' id='sel_school_201' name='sel_school[]' value='201' @if(is_array(old('sel_school')) && in_array(201, old('sel_school'))) checked @endif class='ts_526 ts_2 z_2_8 ts_32 ts_64'>中正國小</label>
      <label for='sel_school_202'><input type='checkbox' id='sel_school_202' name='sel_school[]' value='202' @if(is_array(old('sel_school')) && in_array(202, old('sel_school'))) checked @endif class='ts_526 ts_2 z_2_8 ts_32 ts_64'>育德國小</label>
      <label for='sel_school_203'><input type='checkbox' id='sel_school_203' name='sel_school[]' value='203' @if(is_array(old('sel_school')) && in_array(203, old('sel_school'))) checked @endif class='ts_526 ts_2 z_2_8 ts_32 ts_64'>香田國小</label>
      <label for='sel_school_204'><input type='checkbox' id='sel_school_204' name='sel_school[]' value='204' @if(is_array(old('sel_school')) && in_array(204, old('sel_school'))) checked @endif class='ts_526 ts_2 z_2_8 ts_32 ts_64'>廣興國小</label>
      <label for='sel_school_205'><input type='checkbox' id='sel_school_205' name='sel_school[]' value='205' @if(is_array(old('sel_school')) && in_array(205, old('sel_school'))) checked @endif class='ts_526 ts_2 z_2_8 ts_32 ts_64'>萬興國小</label>
      <label for='sel_school_206'><input type='checkbox' id='sel_school_206' name='sel_school[]' value='206' @if(is_array(old('sel_school')) && in_array(206, old('sel_school'))) checked @endif class='ts_526 ts_2 z_2_8 ts_32 ts_64'>新生國小</label>
      <label for='sel_school_207'><input type='checkbox' id='sel_school_207' name='sel_school[]' value='207' @if(is_array(old('sel_school')) && in_array(207, old('sel_school'))) checked @endif class='ts_526 ts_2 z_2_8 ts_32 ts_64'>中興國小</label>
      <label for='sel_school_212_2' class="text-secondary"><input type='checkbox' id='sel_school_212_2' disabled>原斗國(中)小(含附幼)</label>
      <label for='sel_school_209'><input type='checkbox' id='sel_school_209' name='sel_school[]' value='209' @if(is_array(old('sel_school')) && in_array(209, old('sel_school'))) checked @endif class='ts_526 ts_2 z_2_8 ts_32 ts_64'>萬合國小</label>
<br><button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_township(527);'>527 大城鄉全選</button>
      <label for='sel_school_214'><input type='checkbox' id='sel_school_214' name='sel_school[]' value='214' @if(is_array(old('sel_school')) && in_array(214, old('sel_school'))) checked @endif class='ts_527 ts_2 z_2_8 ts_16 ts_32 ts_64'>大城國小(含附幼)</label>
      <label for='sel_school_216'><input type='checkbox' id='sel_school_216' name='sel_school[]' value='216' @if(is_array(old('sel_school')) && in_array(216, old('sel_school'))) checked @endif class='ts_527 ts_2 z_2_8 ts_16 ts_32 ts_64'>西港國小(含附幼)</label>
      <label for='sel_school_217'><input type='checkbox' id='sel_school_217' name='sel_school[]' value='217' @if(is_array(old('sel_school')) && in_array(217, old('sel_school'))) checked @endif class='ts_527 ts_2 z_2_8 ts_32 ts_64'>美豐國小</label>
    <!--
      <label for='sel_school_218'><input type='checkbox' id='sel_school_218' name='sel_school[]' value='218' @if(is_array(old('sel_school')) && in_array(218, old('sel_school'))) checked @endif class='ts_527 ts_2 z_2_8 ts_32 ts_64'>頂庄國小</label>

      <label for='sel_school_219'><input type='checkbox' id='sel_school_219' name='sel_school[]' value='219' @if(is_array(old('sel_school')) && in_array(219, old('sel_school'))) checked @endif class='ts_527 ts_2 z_2_8 ts_32 ts_64'>潭墘國小</label>
     -->
<br><button  type='button' class='btn btn-warning btn-sm my-1' onclick='set_township(528);'>528 芳苑鄉全選</button>
      <label for='sel_school_227'><input type='checkbox' id='sel_school_227' name='sel_school[]' value='227' @if(is_array(old('sel_school')) && in_array(227, old('sel_school'))) checked @endif class='ts_528 ts_2 z_2_8 ts_16 ts_32 ts_64'>芳苑國小(含附幼)</label>
      <label for='sel_school_229'><input type='checkbox' id='sel_school_229' name='sel_school[]' value='229' @if(is_array(old('sel_school')) && in_array(229, old('sel_school'))) checked @endif class='ts_528 ts_2 z_2_8 ts_32 ts_64'>後寮國小</label>
      <label for='sel_school_248_2' class="text-secondary"><input type='checkbox' id='sel_school_248_2' disabled>民權華德福國(中)小</label>
      <label for='sel_school_231'><input type='checkbox' id='sel_school_231' name='sel_school[]' value='231' @if(is_array(old('sel_school')) && in_array(231, old('sel_school'))) checked @endif class='ts_528 ts_2 z_2_8 ts_32 ts_64'>育華國小</label>
      <label for='sel_school_232'><input type='checkbox' id='sel_school_232' name='sel_school[]' value='232' @if(is_array(old('sel_school')) && in_array(232, old('sel_school'))) checked @endif class='ts_528 ts_2 z_2_8 ts_32 ts_64'>草湖國小</label>
      <label for='sel_school_233'><input type='checkbox' id='sel_school_233' name='sel_school[]' value='233' @if(is_array(old('sel_school')) && in_array(233, old('sel_school'))) checked @endif class='ts_528 ts_2 z_2_8 ts_32 ts_64'>建新國小</label>
      <label for='sel_school_234'><input type='checkbox' id='sel_school_234' name='sel_school[]' value='234' @if(is_array(old('sel_school')) && in_array(234, old('sel_school'))) checked @endif class='ts_528 ts_2 z_2_8 ts_16 ts_32 ts_64'>漢寶國小(含附幼)</label>
      <label for='sel_school_228'><input type='checkbox' id='sel_school_228' name='sel_school[]' value='228' @if(is_array(old('sel_school')) && in_array(228, old('sel_school'))) checked @endif class='ts_528 ts_2 z_2_8 ts_16 ts_32 ts_64'>王功國小(含附幼)</label>
      <label for='sel_school_235'><input type='checkbox' id='sel_school_235' name='sel_school[]' value='235' @if(is_array(old('sel_school')) && in_array(235, old('sel_school'))) checked @endif class='ts_528 ts_2 z_2_8 ts_32 ts_64'>新寶國小</label>
      <label for='sel_school_236'><input type='checkbox' id='sel_school_236' name='sel_school[]' value='236' @if(is_array(old('sel_school')) && in_array(236, old('sel_school'))) checked @endif class='ts_528 ts_2 z_2_8 ts_32 ts_64'>路上國小</label>
      <?php
      $u = explode('/',$_SERVER['REQUEST_URI']);
      ?>
      @if($u[1]=="posts")
      <hr>
      其他<br>
      <label for='sel_school_251'><input type='checkbox' id='sel_school_251' name='sel_school[]' value='251' @if(is_array(old('sel_school')) && in_array(251, old('sel_school'))) checked @endif >特殊教育學校</label>
      <label for='sel_school_252'><input type='checkbox' id='sel_school_252' name='sel_school[]' value='252' @if(is_array(old('sel_school')) && in_array(252, old('sel_school'))) checked @endif >向日葵學園</label>
      <label for='sel_school_253'><input type='checkbox' id='sel_school_253' name='sel_school[]' value='253' @if(is_array(old('sel_school')) && in_array(253, old('sel_school'))) checked @endif >晨陽學園</label>
      <label for='sel_school_254'><input type='checkbox' id='sel_school_254' name='sel_school[]' value='254' @if(is_array(old('sel_school')) && in_array(254, old('sel_school'))) checked @endif >喜樂之家</label>
      <label for='sel_school_255'><input type='checkbox' id='sel_school_255' name='sel_school[]' value='255' @if(is_array(old('sel_school')) && in_array(255, old('sel_school'))) checked @endif >教師研習中心</label>
      @endif
  </div>
</div>



<script>
    function set_township(township_id)
    {
        let allChecked = true;

        $(".ts_" + township_id).each(function() {
            if (!$(this).prop("checked")) {
                allChecked = false;
            }
        });

        // 若全部都有勾選 → 取消全選；否則就全勾
        $(".ts_" + township_id).each(function() {
            $(this).prop("checked", !allChecked);
        });

        // 以下同步雙選欄位
        const ids = [247, 16, 241, 243, 244, 245, 242, 212, 248];

        ids.forEach(function(id) {
            const isChecked = $('#sel_school_' + id).prop("checked");
            $('#sel_school_' + id + '_2').prop("checked", isChecked);
        });

        return false;
    }

    function set_zone(grade, zone)
        {
            let allChecked = true;
            let selector = ".z_" + grade + "_" + zone;

            $(selector).each(function() {
                if (!$(this).prop("checked")) {
                    allChecked = false;
                }
            });

            $(selector).each(function() {
                $(this).prop("checked", !allChecked);
            });

            const ids = [247, 16, 241, 243, 244, 245, 242, 212, 248];

            ids.forEach(function(id) {
                const isChecked = $('#sel_school_' + id).prop("checked");
                $('#sel_school_' + id + '_2').prop("checked", isChecked);
            });

            return false;
         }

    function set_none(township_id){
        $(".ts_"+township_id).each(function() {
            $(".ts_"+township_id).prop("checked", false);
        });

        if($('#sel_school_247').prop("checked")){
            $('#sel_school_247_2').prop("checked",true);
        }else{
            $('#sel_school_247_2').prop("checked",false);
        }

        if($('#sel_school_16').prop("checked")){
            $('#sel_school_16_2').prop("checked",true);
        }else{
            $('#sel_school_16_2').prop("checked",false);
        }

        if($('#sel_school_241').prop("checked")){
            $('#sel_school_241_2').prop("checked",true);
        }else{
            $('#sel_school_241_2').prop("checked",false);
        }

        if($('#sel_school_243').prop("checked")){
            $('#sel_school_243_2').prop("checked",true);
        }else{
            $('#sel_school_243_2').prop("checked",false);
        }

        if($('#sel_school_244').prop("checked")){
            $('#sel_school_244_2').prop("checked",true);
        }else{
            $('#sel_school_244_2').prop("checked",false);
        }

        if($('#sel_school_245').prop("checked")){
            $('#sel_school_245_2').prop("checked",true);
        }else{
            $('#sel_school_245_2').prop("checked",false);
        }

        if($('#sel_school_242').prop("checked")){
            $('#sel_school_242_2').prop("checked",true);
        }else{
            $('#sel_school_242_2').prop("checked",false);
        }

        if($('#sel_school_212').prop("checked")){
            $('#sel_school_212_2').prop("checked",true);
        }else{
            $('#sel_school_212_2').prop("checked",false);
        }

        if($('#sel_school_248').prop("checked")){
            $('#sel_school_248_2').prop("checked",true);
        }else{
            $('#sel_school_248_2').prop("checked",false);
        }

        return false;
    }


    function change_another(){
        if($('#sel_school_247').prop("checked")){
            $('#sel_school_247_2').prop("checked",true);
        }else{
            $('#sel_school_247_2').prop("checked",false);
        }
    }

    function change_another2(){
        if($('#sel_school_16').prop("checked")){
            $('#sel_school_16_2').prop("checked",true);
        }else{
            $('#sel_school_16_2').prop("checked",false);
        }
    }

    function change_another3(){
        if($('#sel_school_241').prop("checked")){
            $('#sel_school_241_2').prop("checked",true);
        }else{
            $('#sel_school_241_2').prop("checked",false);
        }
    }

    function change_another4(){
        if($('#sel_school_244').prop("checked")){
            $('#sel_school_244_2').prop("checked",true);
        }else{
            $('#sel_school_244_2').prop("checked",false);
        }
    }

    function change_another5(){
        if($('#sel_school_243').prop("checked")){
            $('#sel_school_243_2').prop("checked",true);
        }else{
            $('#sel_school_243_2').prop("checked",false);
        }
    }

    function change_another6(){
        if($('#sel_school_245').prop("checked")){
            $('#sel_school_245_2').prop("checked",true);
        }else{
            $('#sel_school_245_2').prop("checked",false);
        }
    }

    function change_another7(){
        if($('#sel_school_242').prop("checked")){
            $('#sel_school_242_2').prop("checked",true);
        }else{
            $('#sel_school_242_2').prop("checked",false);
        }
    }

    function change_another8(){
        if($('#sel_school_212').prop("checked")){
            $('#sel_school_212_2').prop("checked",true);
        }else{
            $('#sel_school_212_2').prop("checked",false);
        }
    }

    function change_another9(){
        if($('#sel_school_248').prop("checked")){
            $('#sel_school_248_2').prop("checked",true);
        }else{
            $('#sel_school_248_2').prop("checked",false);
        }
    }

    $(document).ready(function() {
      var array1 = [ {{ $select_school }} ];
      array1.forEach(function(element) {
          document.getElementById("sel_school_"+element).checked = true;
        });

      // 用來判斷是否 submit 的 flag
      var fSubmit = false;
      $('#this_form').submit(function(e){

          var　category_id=$("#category_id").val();
          if(category_id==5 && $("input[name='sel_school[]']:checked").length===0){
              alert('錯誤：請選擇發送對象學校!');
              $("#submit_button").removeAttr('disabled');
              $("#submit_button").removeClass('disabled');

          } else
              fSubmit = true;

          return fSubmit; // 傳回true就會submit，傳回false就表示不作submit
      });

        if($('#sel_school_247').prop("checked")){
            $('#sel_school_247_2').prop("checked",true);
        }else{
            $('#sel_school_247_2').prop("checked",false);
        }

        if($('#sel_school_16').prop("checked")){
            $('#sel_school_16_2').prop("checked",true);
        }else{
            $('#sel_school_16_2').prop("checked",false);
        }

        if($('#sel_school_241').prop("checked")){
            $('#sel_school_241_2').prop("checked",true);
        }else{
            $('#sel_school_241_2').prop("checked",false);
        }

        if($('#sel_school_243').prop("checked")){
            $('#sel_school_243_2').prop("checked",true);
        }else{
            $('#sel_school_243_2').prop("checked",false);
        }

        if($('#sel_school_244').prop("checked")){
            $('#sel_school_244_2').prop("checked",true);
        }else{
            $('#sel_school_244_2').prop("checked",false);
        }

        if($('#sel_school_245').prop("checked")){
            $('#sel_school_245_2').prop("checked",true);
        }else{
            $('#sel_school_245_2').prop("checked",false);
        }

        if($('#sel_school_242').prop("checked")){
            $('#sel_school_242_2').prop("checked",true);
        }else{
            $('#sel_school_242_2').prop("checked",false);
        }

        if($('#sel_school_212').prop("checked")){
            $('#sel_school_212_2').prop("checked",true);
        }else{
            $('#sel_school_212_2').prop("checked",false);
        }

        if($('#sel_school_248').prop("checked")){
            $('#sel_school_248_2').prop("checked",true);
        }else{
            $('#sel_school_248_2').prop("checked",false);
        }

    });

</script>