// global object defintions (defined in classes.js)
var GameConfig = new ConfigData();
var CoinTots = new Coins();
var CoinRound = new Coins();
var CurrentBlock = new Block();

var current_rent_fee = 0;
var showRent = true;

//
// PROJECT START - called on form load
//
$(document).ready(function ()
{
    $('#btn-rent').click(function () {
        //when rent button is clicked, bank should be decreased, the collected coins will be increased, and lost coin will be 0.
        //after calculation, refreshed the display labels and 
        CoinTots.Bank -= current_rent_fee;
        CoinRound.Collected += CoinRound.Lost;
        CoinRound.Lost = 0;
        CoinRound.Rent = current_rent_fee;//save current rent coins
        DispRoundStats();
        DispOverallStats();
        $("#btn-bank").html("Deposit " + CoinRound.Collected + " Coins");
        $('#coins_lost').addClass("rented");
    });

    $('#finish_button').click(function () {
        if (GameConfig.ExitURL.indexOf("utsbusiness.az1.qualtrics.com") > -1) {
            //http://utsbusiness.az1.qualtrics.com/jfe/form/SV_0cWIP2z6Abz16bH/?userID=test1            
            var userID_array = window.location.href.split("userID=");
            var userID = userID_array[1];
            window.location.href = GameConfig.ExitURL + "/?userID=" + userID;
        } else {
            window.location.href = GameConfig.ExitURL;
        }

    });

    // slow client/server processing means forced sync processing on all ajax calls.
    $.ajaxSetup({async: false});


    // ensure all screens are hidden to begin with
    HideScreens();


    // button defintions
    //$("button").button();
    //$("#btn-login").click(function(){UserLogin()});
    $("#btn-agree").click(function () {
        UserConsent()
    });
    $("#btn-begin").click(function () {
        GameStart()
    });
    $("#btn-buy").click(function () {
        BuyCollector()
    });
    $("#btn-next").click(function () {
        NextRound()
    });
    $("#btn-pbs").click(function () {
        PBS()
    });
    $("#btn-pgs").click(function () {
        PGS()
    });
    $("#btn-bank").click(function () {
        BankCoins()
    });


    // create slider value displays
    $("#pbs-1").slider({
        min: 0,
        max: 10,
        step: .1,
        value: 0,
        slide: function (event, ui) {
            $("#pbs-1>a").html(ui.value);
            // the continue button will be disabled only if both slider should be movement.
            var val = $("#pbs-2").slider('option', 'value');
            if (val > 0)
                $('#btn-pbs').prop("disabled", false);
        }
    });
    $("#pbs-2").slider({
        min: 0,
        max: 10,
        step: .1,
        value: 0,
        slide: function (event, ui) {
            $("#pbs-2>a").html(ui.value);

            var val = $("#pbs-1").slider('option', 'value');
            if (val > 0)
                $('#btn-pbs').prop("disabled", false);
        }
    });
    $("#pgs-1").slider({
        min: 1,
        max: 100,
        step: 1,
        value: 0,
        slide: function (event, ui) {
            $("#pgs-1>a").html(ui.value);
            $('#btn-pgs').prop("disabled", false);
        }
    });
    $("#pgs-2").slider({
        min: 1,
        max: 10,
        step: 1,
        value: 0,
        slide: function (event, ui) {
            $("#pgs-2>a").html(ui.value);
            $('#btn-pgs').prop("disabled", false);
        }
    });



    // user login details
    //user login details has been converted from Sync GET method to Ajax Post.
    // the function is as UserLogin();
    GameConfig.CurrentScreen = "LANDING";
    GameConfig.LoggedUser = '';

    if ($("#user").val().length > 0) {
        $.ajax({
            url: "data.php",
            type: "POST",
            cache: false,
            data: {
                source: "USER",
                action: "GET",
                id: $("#user").val(),
            },
            success: function (Data) {
                Data = JSON.parse(Data);
                if (Data.ID != undefined)
                {
                    // set global object values
                    GameConfig.LoggedUser = Data.ID;
                    GameConfig.CurrentScreen = Data.LastScreen;
                    GameConfig.LastActivity = Data.LastActivity;

                    // hide screen display totals if required
                    if (Data.showTCP == false)
                        $("#tcp").hide();
                    if (Data.showTCC == false)
                        $("#tcc").hide();
                    if (Data.showTCL == false)
                        $("#tcl").hide();
                    if (Data.showCS == false)
                        $("#cs").hide();
                    if (Data.showCB == false)
                        $("#bank").hide();
                    if (Data.showRCP == false)
                        $("#rcp").hide();
                    if (Data.showRCC == false)
                        $("#rcc").hide();
                    if (Data.showRCNC == false)
                        $("#rcnc").hide();
                    if (Data.showRent == false) {
                        $("#btn-rent").hide();
                        $(".intro_show_rent").hide();
                        showRent = false;
                    }
                }
                if (GameConfig.LoggedUser.length == 0)
                {
                    $("#landing").show();
                } else
                {
                    $("#landing").hide();
                    if (GameConfig.CurrentScreen == "") {
                        GameConfig.CurrentScreen = "INTRO";//deleted "CONSENT" and replaced to "INTRO"
                    }
                }
                //UserLogin End

                // grab the config data from the DB
                // load DB values into internal construct                

                CoinTots.Bank = Data.Config.BankStart;

                GameConfig.AdminUser = Data.Config.AdminID;
                GameConfig.Blocks = Data.Config.Blocks;
                GameConfig.Rounds = Data.Config.Rounds;
                GameConfig.Sizes = Data.Config.Sizes.split(',');
                GameConfig.Prices = Data.Config.Prices.split(',');
                GameConfig.Rents = Data.Config.Rents.split(',');
                GameConfig.AnimateCoinInterval = Data.Config.AnimateCoinInterval;
                GameConfig.AnimateCoinSpeed = Data.Config.AnimateCoinSpeed;
                GameConfig.AnimateCoinFade = Data.Config.AnimateCoinFade;
                GameConfig.Penalty = Data.Config.Penalty;//added penalty
                GameConfig.ExitURL = Data.Config.ExitURL;///added exiturl

                if (Data.log_block) {
                    CurrentBlock.Size = parseInt(Data.log_block.Size);
                    CurrentBlock.Rent = parseInt(Data.log_block.Rent);
                    
                    $("#btn-rent").text("Rent Capacity for " + CurrentBlock.Rent + " Coins");
                }

                // update screen intro info
                $(".rounds").each(function () {
                    $(this).html(GameConfig.Rounds);
                });
                $(".total_rounds").each(function () {
                    $(this).html(GameConfig.Blocks * GameConfig.Rounds);
                });

                // navigate to relevant screen
                var ScrArray = GameConfig.CurrentScreen.split(',');
                switch (ScrArray[0])
                {
                    case "LANDING":
                        $("#landing").show();
                        break;
                    case "CONSENT":
                        $("#consent").show();
                        break;
                    case "INTRO":
                        $("#intro").show();
                        //GET method to Ajax
                        $.ajax({
                            url: "data.php",
                            type: "POST",
                            cache: false,
                            data: {
                                source: "USER",
                                action: "SET",
                                scr: "INTRO",
                            },
                            success: function (Data) {
                            }
                        });
                        break;
                    case "CD":
                        SetGameStats(ScrArray);
                        CollectorDecision();
                        break;
                    case "CA":
                        SetGameStats(ScrArray);
                        $("#coin_tots").show();
                        $("#this_round").show();
                        $("#round_start").show();
                        $("#coin_drop").show();
                        $("#btn-buy").prop("disabled", true);
                        $("#btn-next").prop("disabled", true);

                        CoinsAppear();
                        break;
                    case "PBS":
                        SetGameStats(ScrArray);
                        $('#btn-pbs').prop("disabled", true);
                        $("#postblocksurvey").show();
                        PBS();
                        break;
                    case "PGS":
                        SetGameStats(ScrArray);
                        $('#btn-pgs').prop("disabled", true);
                        $("#postgamesurvey").show();
                        PGS();
                        break;
                }
            }
        });
    }


});

//
// User Consent
//
function UserConsent()
{
    GameConfig.CurrentScreen = "INTRO";
    $("#consent").hide();

    $("#intro").show();

}



//
// Post Block Survey Results
// fired by button click
//
function PBS()
{
    Log("PBS");
    $("#pbs-1").slider("value", 0);
    $("#pbs-2").slider("value", 0);
    $("#pbs-1>a").html("");
    $("#pbs-2>a").html("");

    if (CoinTots.CurrentRound == GameConfig.Rounds * GameConfig.Blocks + 1)
    {
        DumpActivity("PGS");
        $("#postblocksurvey").hide();

        $('#btn-pgs').prop("disabled", true);
        $("#postgamesurvey").show();
    } else
    {
        $("#round_start").show();
        CollectorDecision();
    }
}


//
// Post Game Survey Results
// fired by button clicked
//
function PGS()
{
    Log("PGS");
    $("#postgamesurvey").hide();

    $("#completioncode").html(GameConfig.LoggedUser + CoinTots.Bank + Math.floor((Math.random() * 9)));
    $("#credits").show();
}


//
// display current round statistics on screen
//
function DispRoundStats()
{
    $("#this_round .stat").css('opacity', 0);

    $("#curr_round").html(CoinTots.CurrentRound);
    $("#coins_poss_this").html(CoinRound.Possible);
    $("#coins_coll_this").html(CoinRound.Collected);
    $("#coins_lost_this").html(CoinRound.Lost);

    $("#this_round .stat").animate({opacity: 1});

    //rent button will be enabled if lost coins are existed, and current coins in bank are larger than rent fee.

    if (CoinRound.Lost > 0 && CoinTots.Bank > CurrentBlock.Rent) {
        
        var size_index_of_lost = GameConfig.Sizes.indexOf(CoinRound.Lost.toString());
        
        current_rent_fee = GameConfig.Rents[size_index_of_lost];

        // get smallest rent fee        
        if (size_index_of_lost < 0 && GameConfig.Rents.length > 0) {
            var smallest_rent_fee = Number(GameConfig.Rents[0]);
            var i;
            for (i = 0; i < GameConfig.Rents.length; i++) {
                if (Number(GameConfig.Rents[i]) < smallest_rent_fee) {
                    smallest_rent_fee = Number(GameConfig.Rents[i]);
                }
            }
            
            current_rent_fee = smallest_rent_fee;
        }

        $("#btn-rent").text("Rent Capacity for " + current_rent_fee + " Coins");
        if (showRent)
            $("#btn-rent").show();
    } else {
        $("#btn-rent").hide();
    }
}

//
// display overall statistics on screen
//
function DispOverallStats()
{
    $("#all_rounds .stat").css('opacity', 0);

    $("#coins_poss_tot").html(CoinTots.Possible);
    $("#coins_coll_tot").html(CoinTots.Collected);
    $("#coins_lost_tot").html(CoinTots.Lost);
    $("#coins_spent_tot").html(CoinTots.Spent);
    $("#coins_bank_tot").html(CoinTots.Bank);



    $("#all_rounds .stat").animate({opacity: 1});
}



//
// calculate the screen dimensions of a collector
//
function CollectorPixels(Size)
{
    // calculate the collector scale
    //var Min = GameConfig.Sizes[0];
    //var Max = GameConfig.Sizes[GameConfig.Sizes.length-1];
    //var Scale = 375/(Max-Min);

    //return (Size*Scale);
    return(Size * 40 + 10);
}


// 
// populate table of collectors
//
function FillTable(TableName, HasButtons, Selected)
{
    // remove all current table rows
    $("#" + TableName + " tr").remove();

    // add in headings
    var Heading = "<th align='right'></th><th>Size</th><th>Cost</th>";
    if (showRent) {
        Heading += "<th>Rent</th>";
    }
    if (HasButtons)
        Heading += "<th>Buy</th>";
    $("#" + TableName).append("<tr>" + Heading + "</tr>");

    // add in values
    for (var i = 0; i < GameConfig.Sizes.length; i++)
    {
        var Size = GameConfig.Sizes[i];
        var Price = GameConfig.Prices[i];
        var Rent = GameConfig.Rents[i];

        var Width = CollectorPixels(Size);

        var c1 = "<td align='right'><img src='images/slot.png' height='25' width='" + Width + "'></td>";
        var c2 = "<td class='tablecol' style='padding-right: 40px;'>" + Size + "</b></td>";
        var c3 = "<td class='tablecol' style='padding-right: 20px;'>" + Price + " coins </td>";
        var c4 = "";
        if (showRent)
            c4 = "<td class='tablecol' style='padding-right: 20px;'>" + Rent + " coins </td>";
        var c5 = '';
        if (HasButtons)
            c5 = "<td class='tablecol'><input type='radio' name='radio_buy' value='" + i + "'></td>";
        $('#' + TableName).append("<tr id='" + TableName + "_row" + i + "'>" + c1 + c2 + c3 + c4 + c5 + "</tr>");
    }

    //
    $("input:radio[name='radio_buy']").click(function () {
        CollectorSelectClicked($(this).val() * 1 + 1)
    });

    // highlight selected row if required
    if (Selected != null)
    {
        var RowDef = $("#" + TableName + "_row" + Selected);
        RowDef.addClass("selected");
    }
}


//
// Game start following intro
//
function GameStart()
{
    // initial values

    CoinTots.CurrentRound = 1;
    CoinRound.CurrentRound = 1;
    CurrentBlock.Num = 0;

    // UI screen changes
    $("#intro").hide();

    CollectorDecision();
}


//
// Collector Decision
//
function CollectorDecision()
{
    $("#this_round").hide();
    $("#round_start").hide();
    $("#postblocksurvey").hide();
    $("#coin_tots").show();
    $("#btn-buy").prop("disabled", false);
    $("#btn-buy").html("Select a collector");
    $("#btn-bank").prop("disabled", true);
    $("#btn-next").prop("disabled", true);

    DispOverallStats();

    FillTable('buy_collectors', true, null);

    $("#select_collect").show();

    DumpActivity("CD");
}

//
// Collector select clicked
//
function CollectorSelectClicked(value)
{
    $("#btn-buy").html("Buy collector " + value);
}

//
// buy button clicked
//
function BuyCollector()
{
    var CollectorSelected = $("input:checked").val();
    if (CollectorSelected == undefined)
        alert('Please select which collector you would like to buy');
    else
    {
        var Width = CollectorPixels(CollectorSelected);

        // establish current block values then log on DB
        CurrentBlock.Num++;
        CurrentBlock.Cost = parseInt(GameConfig.Prices[CollectorSelected]);
        CurrentBlock.Size = parseInt(GameConfig.Sizes[CollectorSelected]);
        CurrentBlock.Rent = parseInt(GameConfig.Rents[CollectorSelected]);

        Log("BLOCK");

        // screen flips
        $("#select_collect").hide();
        $("#round_start").show();
        $("#coin_drop").show();

        // display selection
        FillTable('bought_collectors', false, CollectorSelected);
        $("#collector_slot").attr("width", Width.toString());
        $("#this_round").show();
        $("#btn-buy").prop("disabled", true);
        $("#btn-next").prop("disabled", true);

        // calculate & display new screen totals

        CoinTots.Spent += CurrentBlock.Cost;
        CoinTots.Bank -= CurrentBlock.Cost;


        CoinsAppear();
        DispRoundStats();
        DispOverallStats();
    }
}



//
// display coins
//
function CoinsAppear()
{

    var CoinDrops = new GetCoins();

    // generate round data & log it

    CoinDrops.SetPossible(CoinTots.CurrentRound);

    $.ajax({
        url: "data.php",
        type: "POST",
        cache: false,
        data: {
            source: "SEQ",
            action: CoinTots.CurrentRound,
        },
        success: function (Data) {


            Data = JSON.parse(Data);
            var GetSeq = Data.Coins;
            CoinDrops.Possible = Data.Coins;
            CoinRound.Possible = CoinDrops.Possible;

            CoinRound.Spent = CurrentBlock.Cost;
            CoinRound.Collected = Math.min(CurrentBlock.Size, CoinDrops.Possible);
            CoinRound.Lost = Math.max(0, CoinRound.Possible - CoinRound.Collected);


//            Log("ROUND");

            // collected coins display
            $("#coin_drop").show();
            $("#btn-bank").html("Deposit " + CoinRound.Collected + " Coins");
            $("#btn-bank").prop("disabled", false);


            while (CoinDrops.Shown < CoinRound.Collected)
                CoinDrops.ShowCoinCollected();

            // pad out to full collector size with 'ghost' coins
            while (CoinDrops.Shown < CurrentBlock.Size)
                CoinDrops.GhostCoin();

            // lost coins display
            while (CoinDrops.Shown < CoinRound.Possible)
                CoinDrops.ShowCoinLost();

            DumpActivity("CA");

            DispRoundStats();
            DispOverallStats();
        }
    });



}



//
// Bank Coins Button Press
//
function BankCoins()
{
    var Interval, CoinNum;


    // every second call coin movement
    Interval = GameConfig.AnimateCoinInterval * 1000;
    for (CoinNum = 0; CoinNum < CoinRound.Collected; CoinNum++)
        setTimeout(CoinMove, CoinNum * Interval, CoinNum);


    // fadeout the lost coins
    for (CoinNum = CoinRound.Collected; CoinNum < CoinRound.Possible; CoinNum++)
        setTimeout(CoinFade, CoinNum * Interval, CoinNum);


    // change screen attribs
    $("#btn-bank").html("Deposit Coins");
    $("#btn-bank").prop("disabled", true);
    $("#btn-rent").hide();


    // update totals

    CoinTots.Possible += CoinRound.Possible;
    CoinTots.Collected += CoinRound.Collected;
    CoinTots.Lost += CoinRound.Lost;
    CoinTots.Bank += Number((CoinRound.Collected + CoinRound.Lost * GameConfig.Penalty).toFixed(1));///+++  


    DispOverallStats();

    // button display
    setTimeout(function () {
        $("#btn-next").prop("disabled", false);
    }, CoinRound.Possible * Interval + 200);
}


//
// Coin Fade
// called on a timer interrupt
//
function CoinFade(i)
{
    $("#coin" + i).fadeOut(GameConfig.AnimateCoinFade * 1000);
}



//
// Coin Animation
// called on a timer interrupt
//
function CoinMove(i)
{
    $("#coin" + i).css("visibility", "hidden");
    $('#coins_move').show();
    $("#coins_move").animate({backgroundPositionX: "+=40"}, GameConfig.AnimateCoinSpeed * 1000, function ()
    {
        $('#coins_move').hide();
        $("#coins_move").css("background-position", "0px 0px");
    });
}


//
// Next Round Button Press
//
function NextRound()
{
    $("#btn-next").prop("disabled", true);
    $('#coins_lost').removeClass("rented");


// In the original logic, log has been occured when round is started, but it needs to work when round is completed
    Log("ROUND");///+++


    if (CoinTots.CurrentRound % GameConfig.Rounds == 0)
    {
        DumpActivity("PBS");
        $("#round_start").hide();
        $("#coin_tots").hide();

        $('#btn-pbs').prop("disabled", true);
        $("#postblocksurvey").show();
        CoinTots.CurrentRound++;
    } else {
        CoinTots.CurrentRound++;
        $("#coin_drop").show();
        CoinsAppear();
        DispRoundStats();
    }

}

//
// Write Log files
//
function Log(Action)
{
    var p1, p2, p3, p4, p5, p6;

    switch (Action)
    {
        case "BLOCK":
            p1 = CurrentBlock.Num;
            p2 = CurrentBlock.Size;
            p3 = -1;
            p4 = -1;
            p5 = -1;
            p6 = CurrentBlock.Rent;//save current rent
            break;
        case "ROUND":
            p1 = CurrentBlock.Num;
            p2 = CoinTots.CurrentRound;
            p3 = CoinRound.Possible;
            p4 = CoinRound.Collected;
            p5 = -1;
            p6 = CoinRound.Rent;//save current rent coins
            break;
        case "PBS":
            p1 = "slider-pbs1";
            p2 = $("#pbs-1").slider("value");
            p3 = "slider-pbs2";
            p4 = $("#pbs-2").slider("value");
            p5 = CurrentBlock.Num;
            Action = "SURVEY";
            break;
        case "PGS":
            p1 = "slider-pgs1";
            p2 = $("#pgs-1").slider("value");
            p3 = "slider-pgs2";
            p4 = $("#pgs-2").slider("value");
            p5 = CurrentBlock.Num;
            Action = "SURVEY";
            break;
    }

    var mydata = {
        source: "LOG",
        action: Action,
        p1: p1,
        p2: p2,
        p3: p3,
        p4: p4,
        p5: p5,
        p6: p6, //current rent
    };

    $.ajax({
        url: "data.php",
        type: "POST",
        cache: false,
        data: {
            source: "LOG",
            action: Action,
            p1: p1,
            p2: p2,
            p3: p3,
            p4: p4,
            p5: p5,
            p6: p6, //current rent
        },
        success: function (Data) {
            CoinRound.Rent = 0;
        }
    });
}


//
// Record current screen status in case of refresh
//
function DumpActivity(Scr)
{
    var ScrData = Scr;
    ScrData += ",bn:" + CurrentBlock.Num;
    ScrData += ",cr:" + CoinTots.CurrentRound;
    ScrData += ",tp:" + CoinTots.Possible;
    ScrData += ",tc:" + CoinTots.Collected;
    ScrData += ",tl:" + CoinTots.Lost;
    ScrData += ",ts:" + CoinTots.Spent;
    ScrData += ",tb:" + CoinTots.Bank;
    ScrData += ",cs:" + (CurrentBlock.Size - 1);
    //get sync to async post
    $.ajax({
        url: "data.php",
        type: "POST",
        cache: false,
        data: {
            source: "USER",
            action: "SET",
            scr: ScrData
        },
        success: function (Data) {

        }
    });
}

//
// Read current screen status on refresh
//
function SetGameStats(Tots)
{

    for (var i = 1; i < Tots.length; i++)
    {
        var split = Tots[i].split(':');
        var num;
        if (split[0] != "tb")
            num = parseInt(split[1]);
        else
            num = parseFloat(split[1]);

        switch (split[0])
        {
            case "bn":
                CurrentBlock.Num = num;
                break;
            case "cr":
                CoinTots.CurrentRound = num;
                break;
            case "tp":
                CoinTots.Possible = num;
                break;
            case "tc":
                CoinTots.Collected = num;
                break;
            case "tl":
                CoinTots.Lost = num;
                break;
            case "ts":
                CoinTots.Spent = num;
                break;
            case "tb":
                CoinTots.Bank = num;

                break;
            case "cs":
                FillTable('bought_collectors', false, num);
                break;
        }
    }
}


//
// Hide all HTML screens scections
//
function HideScreens()
{
    //$("#landing").hide();
    $("#intro").hide();
    $("#consent").hide();
    $("#coin_tots").hide();
    $("#round_start").hide();
    $("#select_collect").hide();
    $("#postblocksurvey").hide();
    $("#postgamesurvey").hide();
    $("#admin").hide();
    $("#credits").hide();
}


