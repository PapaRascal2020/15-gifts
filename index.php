<?php

$result = "";

if(isset($_POST['number'])){
    $num = $_POST['number'];
    $result = numberToEnglish($num);
}

function numberToEnglish(int $number)
{
    if($number === 0) return "Zero";

    $numberLength = strlen($number);

    $wordList1 = ['','One','Two','Three', 'Four', 'Five', 'Six','Seven', 'Eight', 'Nine','Ten', 'Eleven', 'Twelve','Thirteen', 'Fourteen', 'Fifteen','Sixteen', 'Seventeen', 'Eighteen','Nineteen'];
    $wordList2 = ['', 'Ten', 'Twenty', 'Thirty','Forty', 'Fifty', 'Sixty','Seventy', 'Eighty', 'Ninety', 'Hundred'];
    $wordList3 = ['','thousand','million'];

    $levels = ( int ) ( ( $numberLength + 2 ) / 3 );
    $maxLength = $levels * 3;
    $num    = substr( '00'.$number , -$maxLength );
    $num_levels = str_split( $num , 3 );

    foreach( $num_levels as $num_part )
    {
        $levels--;
        $hundreds   = ( int ) ( $num_part / 100 );
        $hundreds   = ( $hundreds ? ' ' . $wordList1[$hundreds] . ' Hundred' . ' ' : '' );
        $tens       = ( int ) ( $num_part % 100 );
        $singles    = '';
        
        if( $tens < 20 ) { 
            $tens = ( $tens ? ' ' . $wordList1[$tens] : '' ); 
        } else { 
            $tens = ( int ) ( $tens / 10 ); 
            $tens = ' ' . $wordList2[$tens] . ''; 
            $singles = ( int ) ( $num_part % 10 ); 
            $singles = ' ' . $wordList1[$singles] . ' '; 
        } 
        
        $englishWords[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $wordList3[$levels] : '' ); 
    }

    $words = implode( ' ' , $englishWords);

    $words = ltrim($words);
    $words = rtrim($words);

    $words = explode(" ", strtolower($words));

    $keywords1 = ['million', 'thousand'];
    $keywords2 = ['twenty', 'thirty', 'fourty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    $keywords3 = ['hundred'];

    $finalString = "";
    $words[0] = ucwords($words[0]);

    for($i = 0; $i < count($words); $i++)
    {
        if(in_array($words[$i], $keywords1) && $i != count($words) - 1) {
            if(((count($words) - 1) - $i) <= 3)
            {
                    $finalString .= ' '. $words[$i] . ' and ';               
            } else {
                $finalString .= ' '. $words[$i] . ', ';
            }
            
        } 
        else if(in_array($words[$i], $keywords2) && $i != count($words) - 1)
        {
            $finalString .= ' '. $words[$i] . '-' . $words[$i+1];
            $i++;
        }
        else if(in_array($words[$i], $keywords3) && $i != count($words) - 1)
        {
            if(in_array($words[$i+1], $keywords1))
            {
                $finalString .= ' '. $words[$i] . ' ';
            }
            else 
            {
                $finalString .= ' '. $words[$i] . ' and ';
            }
            
        }
        else
        {
            $finalString .= ' ' . $words[$i];
        }

        
    }

    return $finalString;
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Number Converter</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
 
<div class="container">
  <h2>Convert Numbers To Word Form</h2>
  <form method="post">
    <div class="form-group">
      <label for="number">Enter a number to convert:</label>
      <input type="number" class="form-control" id="number" required placeholder="Enter Your Numbers" name="number">
    </div>
    <button type="submit" class="btn btn-default">Convert</button><br/><br/>
  </form>

  <h4 align='center'><?= $result ?></h4>
</div>
</body>
</html>