<?php session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'cashier') {header("Location: ../logout.php");exit();}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="flex mr-[350px] bg-blue-200">
        <div class="pb-10 pt-5 px-5">
            anjay
        </div>



        <div class="bg-gray-300 w-[350px] z-30 pt-5 pb-10 px-10 fixed h-screen right-0 drop-shadow-xl">
            
            <div class=" text-sm mb-10">
                <div class="grid grid-cols-3 items-center border-b pb-2 mb-2">
                    <p class="col-span-2"><span class="font-semibold">Nasi Goreng Cikur</span><br>
                    <span class=" text-xs">2x | Rp 15.000</span></p>
                    <p class="text-end font-medium">Rp 30.000</p>
                </div>
                <div class="grid grid-cols-3 items-center border-b pb-2 mb-2">
                    <p class="col-span-2"><span class="font-semibold">Nasi Goreng Cikur</span><br>
                    <span class=" text-xs">2x | Rp 15.000</span></p>
                    <p class="text-end font-medium">Rp 30.000</p>
                </div>
                <div class="grid grid-cols-3 items-center border-b pb-2 mb-2">
                    <p class="col-span-2"><span class="font-semibold">Nasi Goreng Cikur</span><br>
                    <span class=" text-xs">2x | Rp 15.000</span></p>
                    <p class="text-end font-medium">Rp 30.000</p>
                </div>
                <div class="grid grid-cols-3 items-center border-b pb-2 mb-2">
                    <p class="col-span-2"><span class="font-semibold">Nasi Goreng Cikur</span><br>
                    <span class=" text-xs">2x | Rp 15.000</span></p>
                    <p class="text-end font-medium">Rp 30.000</p>
                </div>
            </div>
            
            <div>
                <p class="text-sm flex justify-between">Subtotal <span class="font-medium">Rp 120.000</span></p>
                <p class="text-sm flex justify-between">Pajak (2%) <span class="font-medium">Rp 2.400</span></p>
                <p class="text-base flex justify-between mt-2">TOTAL <span class="font-medium">Rp 122.400</span></p>
            </div>
            
            <div class="grid grid-cols-2 gap-1 px-5 mt-14">
                <a href="" class="text-center bg-slate-100 rounded-md py-1">Batalkan</a>
                <a href="" class="text-center bg-slate-100 rounded-md py-1">Lanjutkan</a>
            </div>
        </div>
    </div>
    
    
    <div id="navbar"></div>
    
    <script src="script.js"></script>
    <script></script>
    <!-- <script src="../jquery.js"></script> -->
    <!-- <a href="../logout.php">keluar</a> -->
</body>
</html>