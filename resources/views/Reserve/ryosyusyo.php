<?php

include('./dbAccess.php');
require_once('TCPDF/tcpdf.php');
require_once('TCPDF/fpdi/autoload.php');
$pdfFilename = 'doc/ryosyusyo.pdf';

$HaisoText	 = ['', '現場引き取り', '配　送', '混　在'];
$PaysWayText = ['', '代金引換', '銀行振込', 'PayPay支払', 'その他'];


$pdf = new setasign\Fpdi\Tcpdf\Fpdi();

$page = $pdf->setSourceFile($pdfFilename);

$pdf->SetMargins(0, 0, 0); //マージン無効
$pdf->SetAutoPageBreak(false); //自動改ページ無効
$pdf->setPrintHeader(false); //ヘッダー無効
$pdf->setPrintFooter(false); //フッター無効

//最初の1ページを取得
$pdf->AddPage('P'); //P：縦(既定なので指定しなくてもよい)/L：横
$first = $pdf->importPage(1);
$pdf->useTemplate($first);
 
$pdf->SetTextColor(0, 0, 0); //文字色

$reqOrder = $_GET['OrderNo'];
$prmKeyStr = $_GET['KeyStr'];

$Mysqli = OpenDataBase();
	
//	注文データの検索
$sql =	"select  TA.OrderNo,TA.ReqDate,TB.OrderTanka,TB.OrderCount,TC.SyohinName,TC.SyohinUnit,TC.SyohinLong,TC.Price,TB.IsHaiso,TC.Haiso,TC.DispUnit,
            TA.ClitNameKanji, TA.ClitNameKana, TA.CliAddrZip,TA.CliAddrPref,TA.CliAddrCity,TA.KeyStr,
            TA.CliAddrOther,TA.CliTel1,TA.CliTel2,TA.CliFax,TA.CliEMail,TA.WaysPay,TA.Hikiwatasi,
            TA.DiscountCharge,TA.AddDelivCharge,TA.AddCommision,TA.GasolineCost,TA.CompDate,TA.Status
        from maki10_order AS TA
        RIGHT JOIN maki11_detail AS TB ON TA.OrderNo=TB.OrderNo
        RIGHT JOIN maki02_syohin AS TC ON TB.ProductNo=TC.SyohinNo
        where TA.ReqType=0
        and TA.OrderNo=$reqOrder and TA.KeyStr='$prmKeyStr'
        order by TA.ReqDate,TB.ProductNo";

$AllPrice = 0;
$result = QueryDataBase($Mysqli,$sql);
$showAddr = "";
$bFirst = TRUE;

$Haiso = 0;
$rdoWaysPay = 0;

function ShowOtherPrice($title,$price,$lineY)
{
    global $pdf;

    $pdf->SetXY(14, $lineY);
    $pdf->Write(10, $title);

    $pdf->SetXY(120, $lineY);
    $pdf->Write(10, sprintf('%7s',number_format($price)));

    $pdf->SetXY(160, $lineY);
    $pdf->Write(10, sprintf('%7s',number_format($price)));
}

if($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ReqDate	= $row["ReqDate"];
    $NameKanji	= $row["ClitNameKanji"] . ' 様';
    $wkHiki		= $row["Hikiwatasi"];
    $showAddr	= $row["CliAddrPref"] . $row["CliAddrCity"] . $row["CliAddrOther"];
    $ixStatus	= $row["Status"];
    $Haiso      = $row["Haiso"];
    $DiscountCharge      = $row["DiscountCharge"];
    $AddDelivCharge      = $row["AddDelivCharge"];
    $AddCommision      = $row["AddCommision"];
    $GasolineCost      = $row["GasolineCost"];
    $rdoWaysPay = $row["WaysPay"];

    //氏名
    $pdf->SetFont('kozminproregular', '', 14); //フォントの設定
    $pdf->SetXY(12.5, 35);
    $pdf->Write(10,$NameKanji);

    $pdf->SetFont('kozminproregular', '', 10); //フォントの設定

    //注文番号
    $pdf->SetXY(140, 30.9);
    $pdf->Write(10, sprintf('%4s',$reqOrder));
    //発行日
    $pdf->SetXY(140, 36.5);
    $pdf->Write(10, date('Y年n月j日'));

    //  ご注文日
    $pdf->SetXY(37, 53.7);
    $pdf->Write(10,dateOut($ReqDate));

    if($row['CompDate'] != '') {
        $DateStr = $row['CompDate'];
        if(substr($DateStr, 0 , 4) == "1980") {
            $DateStr = "未定";
        }
        $pdf->SetXY(37, 60.2);
        $pdf->Write(10, dateOut($DateStr));

        if($rdoWaysPay == 1) {
            $pdf->SetXY(37, 66.7);
            $pdf->Write(10, dateOut($DateStr));
        }
    }

    $lineY = 116;
    do {
        $wkReqDate = str_replace(' ', '<br>',$row["ReqDate"]);
        $wkPrice = $row["OrderTanka"];	//	販売時点での単価
        $HaisoDisp = $row["IsHaiso"];
        if($HaisoDisp == 2) {			//	配送ならば配送単価を加算
            $wkPrice += $row["Haiso"];
        }
        $wkTotal = $wkPrice * $row["OrderCount"];	//	小計を計算

        $pdf->SetXY(14, $lineY);
        $ShohinLine = $row["SyohinName"];
        if($row["SyohinLong"] > 0) {
            $ShohinLine .= '( ' . $row["SyohinLong"] . 'cm)';
        }
        $ShohinLine .= '　' . $row["SyohinUnit"];
        $pdf->Write(10, $ShohinLine );

        $pdf->SetXY(84, $lineY);
        $pdf->Write(10, sprintf('%3s',$row["OrderCount"]));

        $pdf->SetXY(103, $lineY);
        $pdf->Write(10, $row["DispUnit"]);

        $pdf->SetXY(120, $lineY);
        $pdf->Write(10, sprintf('%7s',number_format($wkPrice)));

        $pdf->SetXY(160, $lineY);
        $pdf->Write(10, sprintf('%7s',number_format($wkTotal)));

        $AllPrice += $wkTotal;
        $lineY += 6;
    }
    while ($row = $result->fetch_assoc());

    if($DiscountCharge > 0) {
        ShowOtherPrice("値引き",$DiscountCharge,$lineY);
        $AllPrice -= $DiscountCharge;
        $lineY += 6;
    }

    if($AddDelivCharge > 0) {
        ShowOtherPrice("追加配送料",$AddDelivCharge,$lineY);
        $AllPrice += $AddDelivCharge;
        $lineY += 6;
    }

    if($AddCommision > 0) {
        ShowOtherPrice("その他手数料",$AddCommision,$lineY);
        $AllPrice += $AddCommision;
        $lineY += 6;
    }

    if($GasolineCost > 0) {
        ShowOtherPrice("ガソリン代",$GasolineCost,$lineY);
        $AllPrice += $GasolineCost;
        $lineY += 6;
    }


    $AllPrice = number_format($AllPrice);
    $pdf->SetXY(160, 182);  //  下の合計欄
    $pdf->Write(10, sprintf('%7s',$AllPrice));

    #$pdf->SetXY(20, 300);
    #$pdf->Write(10, $HaisoText[$Haiso]);

    $pdf->SetXY(20, 308);
    $pdf->Write(10, $PaysWayText[$rdoWaysPay]);


    $pdf->SetFont('kozminproregular', '', 14); //フォントの設定
    $pdf->SetXY(42, 96);
    $pdf->Write(10, '￥' . $AllPrice . '.-'); //  上の合計欄
}

$Mysqli->close();	// データベース切断

//2ページ移行を取得
for ($i = 2; $i <= $page; $i++) {
    $pdf->AddPage();
    $tpl = $pdf->importPage($i);
    $pdf->useTemplate($tpl);
}

//画面に表示させる場合は、こちら
$pdf->Output('');

function dateOut($dateStr) {
    return substr($dateStr,0,4) . "年" . substr($dateStr,5,2) . "月" . substr($dateStr,8,2) . "日　" ;
}



