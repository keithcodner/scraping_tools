#Get-ChildItem -Path C:\Git\camis\Testing\*.ts -recurse |  Select-String -Pattern "create/change/cancel booking with emergency contacts" 

$tests = @("create and update stay length on backcountry zone reservation via calendar view", "create and edit backcountry zone reservation via calendar view")

$main_path = "C:\Git\camis\Testing\*.ts"

$start = "yarn test:debugspecific -- .\build\"
$test_name = "'create and cancel marina reservation'"
$combined_print = "cd C:\Git\camis\Testing\Prime.TestCafe" + "`r`n"

For ($i=0; $i -lt $tests.Length; $i++) {
    $test_name = $tests[$i]

    $data = Get-ChildItem -Path $main_path -recurse | Select-String -Pattern $test_name | out-string
    $data = $data.replace('C:\Git\camis\Testing\Prime.TestCafe\','')
    $data_arr = $data -split ":"
    $dataz = $data_arr[0].replace('.ts','.js')

    $combined = $start.Trim() +  $dataz.Trim() + ' -t "' + $test_name + '"'
    $combined_print += $combined + "`r`n" 

    Write-Host $combined
}

$combined_print | Out-File -FilePath C:\Users\keith.codner\Desktop\first-file.txt
 

Read-Host -Prompt "Press any key to continue"