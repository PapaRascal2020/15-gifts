<?php

$result = "";

if(isset($_POST['number'])){

    $converter = new numberToWords();
    $result = $converter->spellout($_POST['number'], true);
}

class numberToWords {

    const SINGLE_DIGITS = ['','one','two','three', 'four', 'five', 'six','seven', 'eight', 'nine','ten', 
    'eleven', 'twelve','thirteen', 'fourteen', 'fifteen','sixteen', 'seventeen', 'eighteen','nineteen'];

    const DOUBLE_DIGITS = ['', 'ten', 'twenty', 'thirty','forty', 'fifty', 'sixty','seventy', 'eighty', 'ninety'];

    const OTHER_DENOMINATORS = ['','thousand','million'];

    public function spellout(int $number, bool $prettify = false): string
    {
        if($number === 0) return "Zero";

        $numberLength = strlen($number);

        $levels = ( int ) ( ( $numberLength + 2 ) / 3 );
        $maxLength = $levels * 3;
        $num    = substr( '00'.$number , -$maxLength );
        $num_levels = str_split( $num , 3 );

        foreach( $num_levels as $num_part )
        {
            $levels--;
            $hundreds   = ( int ) ( $num_part / 100 );
            $hundreds   = ( $hundreds ? ' ' . self::SINGLE_DIGITS[$hundreds] . ' hundred' . ' ' : '' );
            $tens       = ( int ) ( $num_part % 100 );
            $singles    = '';
            
            if( $tens < 20 ) { 
                $tens = ( $tens ? ' ' . self::SINGLE_DIGITS[$tens] : '' ); 
            } else { 
                $tens = ( int ) ( $tens / 10 ); 
                $tens = ' ' . self::DOUBLE_DIGITS[$tens] . ''; 
                $singles = ( int ) ( $num_part % 10 ); 
                $singles = ' ' . self::SINGLE_DIGITS[$singles] . ' '; 
            } 
            
            $englishWords[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . self::OTHER_DENOMINATORS[$levels] : '' ); 
        }

        $englishWords = $this->trimAll(implode( ' ' , $englishWords));

        if($prettify)
        {
            return $this->prettify(explode(" ", $englishWords));
        } 
        
        return $englishWords;
    } 

    protected function trimAll(string $string): string
    {
        return rtrim(ltrim($string));
    }

    protected function prettify(array $words): string
    {
        $keyParts1 = ['million', 'thousand'];
        $keyParts2 = ['twenty', 'thirty', 'fourty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
        $keyParts3 = ['hundred'];

        $sentence = "";
        $wordCount = count($words);

        $words[0] = ucwords($words[0]);

        for($i = 0; $i < $wordCount; $i++)
        {
            if(in_array($words[$i], $keyParts1) && $i != $wordCount - 1) {
                if((($wordCount - 1) - $i) <= 3)
                {
                    $sentence .= ' '. $words[$i] . ' and ';               
                } else {
                    $sentence .= ' '. $words[$i] . ', ';
                }
                
            } 
            else if(in_array($words[$i], $keyParts2) && $i != $wordCount - 1)
            {
                $sentence .= ' '. $words[$i] . '-' . $words[$i+1];
                $i++;
            }
            else if(in_array($words[$i], $keyParts3) && $i != $wordCount - 1)
            {
                if(in_array($words[$i+1], $keyParts1))
                {
                    $sentence .= ' '. $words[$i] . ' ';
                }
                else 
                {
                    $sentence .= ' '. $words[$i] . ' and ';
                }
                
            }
            else
            {
                $sentence .= ' ' . $words[$i];
            }
        }

        return $sentence;

    }
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
    <body style="background-color: #444444;">
        <div style="width: 500px; margin: auto; position:absolute; top:50%; left:50%; margin-top:-200px;  margin-left:-250px;">
            <form method="post">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header border-bottom-0">
                        <h2 class="modal-title">Number to English Converter</h2>
                    </div>
                    <div class="modal-body py-0">
                        <div class="form-group">
                            <label for="number">Enter a number to convert:</label>
                            <input type="number" class="form-control" id="number" required placeholder="Enter Your Numbers" name="number">
                        </div>

                        <h4 style="margin-top: 20px; padding: 5px; border: 1px solid #999;"><?= $result ?></h4>

                    </div>
                    <div class="modal-footer flex-column border-top-0">
                        <button type="submit" class="btn btn-lg btn-primary w-100 mx-0 mb-2">Convert</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>