#Get-ChildItem -Path C:\Git\camis\Testing\*.ts -recurse |  Select-String -Pattern "create/change/cancel booking with emergency contacts" 

#******Add-To-Folder-Path******
# Takes 3 Params
#First param is the path you want to alter, the second param is the position where to insert and the 3rd param is the folder name that should be inserted

$folder_path_arr = @("D:\games 'n stuff\@SVG etsy work\@SVG_Catelog\@For Sale\143. emergency and law enforcement - svg bundle   ---- new start\raw", "D:\games 'n stuff\@SVG etsy work\@SVG_Catelog\@For Sale\95. home - svg bundle @gathered\raw", "D:\games 'n stuff\@SVG etsy work\@SVG_Catelog\@For Sale\96. business - svg bundle @gathered\raw", "D:\games 'n stuff\@SVG etsy work\@SVG_Catelog\@For Sale\97. travel - svg bundle @gathered\raw", "D:\games 'n stuff\@SVG etsy work\@SVG_Catelog\@For Sale\98. school - svg bundle @gathered\raw")

$magick_path = "C:\Program Files\ImageMagick-7.1.1-Q16-HDRI\magick.exe"
$potrace_path = "C:\inkscape\potrace\potrace.exe"

Function Add-To-Folder-Path($pathz, $pos, $folder){
    $path_arr = $pathz.split("\")
    $max_el = $path_arr.Length
    $final_path = ""
    
    $counterz = 0
    foreach($p in $path_arr){

        if($path_arr[$counterz] -eq ""){
            #do nothing
        }else{
            if( $counterz -eq 0){
                $final_path += $path_arr[$counterz]
            }else{
                if($counterz -eq  [int]$pos){
                    $final_path += "\"+$folder
        
                }elseif($counterz -eq  [int]$pos){
                    $final_path += "\"+$folder
                }
                $final_path += "\"+$path_arr[$counterz]
                
                #Write-Host $path_arr[$counterz]
            }
        }

        $counterz++
    }

    return $final_path 
}

Function Add-Market-Display($pathz){
    #$new_pathz = Add-To-Folder-Path  $pathz "7" "work"
    #$new_pathz = $pathz + "\" + $workfolder + "\"
    $pathz = $pathz
    $m_args_1 = 'montage'
    $m_args_2 = "$pathz\work\*.png"
    $m_args_3 = "$pathz\work\out.png"
    #, "$pathz\work\*.png",  "$pathz\work\out.png"
    #, "-density 300", "-tile 5x5", "-geometry +50+50", "-border 1" #cant get options to work, but its ok
    Write-Host $m_args_1
    & $magick_path $m_args_1 $m_args_2 $m_args_3

    #montage -density 300 -tile 2x0 -geometry +5+50 -border 10 *.png out.png
}

#paths for x99


#paths for imac air
#$folder_path_arr = @("C:\Users\kjblue\Desktop\junk\banners", "C:\Users\kjblue\Desktop\junk\banners")

#paths for asus mini
#$folder_path_arr = @("", "")

#$main_path = "C:\Git\camis\Testing\*.ts"

#loop through each patb
For ($i=0; $i -lt $folder_path_arr.Length; $i++) {
    #assign variable
    $individual_folder = $folder_path_arr[$i]
    $individual_work_folder = $individual_folder + "\work"

    #get contents of folder and assign it to an array
    $child_files = Get-ChildItem -Path $individual_folder

    #Write-Host $child_files

    #delete folder and contents if thry ezsist
    Remove-Item -LiteralPath $individual_work_folder -Force -Recurse
    #create a new folder after the previous one is deleted
    mkdir $individual_work_folder

    #loop through of the folder paths contents
    foreach($c in $child_files){
        $outfile = $c.FullName
        $outfile_with_work_path = Add-To-Folder-Path  $outfile "7" "work" #mod work folder path
        $outfile_no_ext = $outfile_with_work_path.replace(".png", "") #remove original name/etx
        $new_outfile_renamed_flat = $outfile_no_ext + "_flattened_png" + ".png" # add new name with ext back /clean up
        $new_outfile_renamed_pnm = $outfile_no_ext + "_bitmap" + ".pnm" # add new name with ext back /clean up
        $new_outfile_renamed_svg = $outfile_no_ext + "_svg" + ".svg" # add new name with ext back
        $new_outfile_renamed_png = $outfile_no_ext + "_new_png" + ".png" # add new name with ext back
        $new_outfile_renamed_eps = $outfile_no_ext + "_eps" + ".eps" # add new name with ext back
        $new_outfile_renamed_bmp = $outfile_no_ext + "_bmp" + ".bmp" # add new name with ext back /clean up
        $new_outfile_renamed_jpg = $outfile_no_ext + "_jpg" + ".jpg" # add new name with ext back
        $new_outfile_renamed_dxf = $outfile_no_ext + "_dxf" + ".dxf" # add new name with ext back
        
        #****CONVERT TO SVG SPACE*****
        #convert 1 - raw png, to flat file
        $m_args_1 = "convert", "-flatten", $outfile, $new_outfile_renamed_flat #create args
        & $magick_path $m_args_1 #append args, and execute

        #convert 2 - flattened png, to bitmap file
        $m_args_2 = "convert", "-flatten", $new_outfile_renamed_flat, $new_outfile_renamed_pnm
        & $magick_path $m_args_2

        #convert 3 - flattened png, to bitmap file
        $m_args_3 = $new_outfile_renamed_pnm, "-s", "-o", $new_outfile_renamed_svg
        & $potrace_path $m_args_3

        #****CONVERT TO EPS SPACE*****
        #convert 1 - raw png, to bmp file
        $m_args_4 = "convert", $new_outfile_renamed_flat, $new_outfile_renamed_bmp #create args
        & $magick_path $m_args_4

        #convert 2 - flattened bmp, to eps file
        $m_args_5 = $new_outfile_renamed_bmp, "-e", "-o", $new_outfile_renamed_eps
        & $potrace_path $m_args_5

        #****CONVERT TO JPG SPACE*****
        #convert 1 - 
        $m_args_6 = "convert", $new_outfile_renamed_flat, $new_outfile_renamed_jpg
        & $magick_path $m_args_6

        #****CONVERT TO PNG SPACE*****
        #convert 1 - 
        $m_args_7 = "convert", $outfile, $new_outfile_renamed_png
        & $magick_path $m_args_7

        #****CONVERT TO DXF SPACE*****
        #convert 1 - 
        $m_args_8 = $new_outfile_renamed_bmp, "-dxf", "-o", $new_outfile_renamed_dxf
        & $potrace_path $m_args_8

        #potrace archs.pnm -s -o archx.svg

        #****DELETS TEMP FILES*****
        Remove-Item $new_outfile_renamed_flat 
        Remove-Item $new_outfile_renamed_pnm
        Remove-Item $new_outfile_renamed_bmp


        #Write-Host $new_outfile_renamed
    }

    #create marketing display png
    Add-Market-Display($individual_folder)

    $outfile_market_png = $individual_work_folder+'\out.png'
    $outfile_no_ext_market = $outfile_market_png.replace(".png", "")
    $market_outfile_renamed_bmp = $outfile_no_ext_market + "_bmp" + ".bmp"
    $market_outfile_renamed_eps = $outfile_no_ext_market + "_eps" + ".eps"

    #****CONVERT TO EPS SPACE*****
    #convert 1 - raw png, to bmp file
    $market_args_1 = "convert", $outfile_market_png, $market_outfile_renamed_bmp #create args
    & $magick_path $market_args_1

    #convert 2 - flattened bmp, to eps file
    $market_args_2 = $market_outfile_renamed_bmp, "-e", "-o", $market_outfile_renamed_eps
    & $potrace_path $market_args_2


}
 

#how to call a ps function functions(crazy i know)
Add-To-Folder-Path "D:\games 'n stuff\@SVG etsy work\@SVG_Catelog\@For Sale\104. wedding and marriage - svg bundle @gathered\raw" "6" "work"

Read-Host -Prompt "Press any key to continue"