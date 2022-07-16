<? 

    /*----------------------------------------------------------------------------------

    Title: Stupid-Simple Image Optimization Script
    Version: 1.0
    Author: Will Leffert
    Contributors: A bit of Stack Overflow and a lot of Yoo-Hoo.
    License: MIT. Do whatever you like.
    Requirements: A recent PHP (5/7) & GD; Did testing on 7.1 for giggles. Really not
        much to worry about, but some versions of 5.x don't play well with GD, FYI.
    Purpose: A clean, stripped down script that can be used to compress images on call.
        I've been dealing with inventory systems that have uncompressed images in their
        CSV; good dimensions, but uncompressed. Not good for load times or SEO. So, I
        decided to create this and share it, as I've never actually posted anything to
        GitHub before, and figured.. Let's start now.
    Future Plans: If I ever get bored and decide to add new features, I'll probably add
        in image sizing. Possibly additional file format support, too, and a format
        switcher. Image sizing is obviously a good feature, but.. I HATE doing image
        scaling with GD/PHP, and after trying to figure out why it didn't work during
        the intial dev, I dropped it; no sense in putting it in currently, as it isn't
        needed for the original purpose of the script.
    Usage: Stupid-simple. Just change the defines right below here to meet your needs.
        I'd recommend storing it in a non-public folder (.htaccess is your friend) and
        scheduling it with CRON, otherwise someone could just hammer this script and
        waste cycles.. Which, honestly, shouldn't be much of an issue unless you've got
        a REALLY slow server. This is a clean, fast way to optimize images without
        bloat.

    ----------------------------------------------------------------------------------*/

    define("IMGFOLDER",         "images/"); //Specify folder that has the original images; you'll need 755 permissions in the folder
    define("IMGOUTPUTFOLDER",   "images/"); //Specify output folder; you'll need 755 permissions in the folder. Can be the same, or different from input folder.
    define("IMGCOMPRESSION",    40);  //Amount of compression, from 1-100.
    define("IMGLOGLOC",         "imglog.json"); //log file name and location. If not found, it'll create it, no worries.

    // Pull up the folder and pull the image file names to return an array with a date for later checks
   function grabImages() {
        if (is_dir(IMGFOLDER)){
            if ($dh = opendir(IMGFOLDER)){
                while (($file = readdir($dh)) !== false){
                    if (strpos($file, '.jpg') !== false){
                        $imgFiles[] = [ 
                            'lastmod' => filemtime(IMGFOLDER . $file), 
                            'filename' => $file,
                        ];
                    }

                }
                closedir($dh);
            }
        }
        return $imgFiles;
    }   

    //Snagged this from Stack Overflow; this has to be the best implementation of multi-dimensional associative array comparison I've seen. So clean!
    function check_diff_multi($arrayA, $arrayB) {
        foreach ($arrayA as $keyA => $valueA) {
            if (in_array($valueA, $arrayB)) {
                unset($arrayA[$keyA]);
            }
        }
        return $arrayA;
    }

    //grab the original log, and if it's an actual log, compare to folder to get new files and merge for new log.. Or just put new files into brand new log
    $imgLogOrig = json_decode(file_get_contents(IMGLOGLOC), true);
 
    if (is_array($imgLogOrig)) {
        $imgsToCompress = check_diff_multi(grabImages(), $imgLogOrig);
        $imgFilesAll = array_merge(grabImages(), $imgsToCompress);
    } else {
        $imgsToCompress = grabImages();
        $imgFilesAll= grabImages();
    }
    
    //This grabs the image, processes it, and then saves it to the output folder
    foreach($imgsToCompress as $theImg) {
        $workingImg = imagecreatefromjpeg(IMGFOLDER . $theImg['filename']);
        imagejpeg($workingImg, IMGOUTPUTFOLDER . $theImg['filename'], IMGCOMPRESSION);
        imagedestroy($workingImg);
    }

    //This generates the log file, plus some logic to handle if you're overriting existing files instead of putting in a new dir..
    if (IMGFOLDER == IMGOUTPUTFOLDER) {
        //We'll just rerun grabImages
        $imgFilesAll = grabImages();
    }
    $imgLog = fopen(IMGLOGLOC, 'w');
    fwrite($imgLog, json_encode($imgFilesAll));
    fclose($imgLog);
    
?>