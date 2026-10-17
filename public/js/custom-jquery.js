$(document).ready(function () {
    $("#offerOptionMessage").hide();
    $("#emailNew").hide();
    $("#nameMessage").hide();
    $("#emailMessage").hide();
    $("#passwordMessage").hide();
    $("#organisationMessage").hide();
    $("#countryMessage").hide();
    $("#emailValidationMessage").hide();
    $("#sponsoredTypeMessage").hide();
    $("#sponsoredCountryMessage").hide();
    $("#sponsoredBudgetMessage").hide();
    $("#sponsoredIndustryMessage").hide();
    $("#sponsorTypeMessage").hide();
    $("#sponsorCountryMessage").hide();
    $("#sponsorBudgetMessage").hide();
    $("#sponsorIndustryMessage").hide();
    $("#sponsoredOtherSpecify").hide();
    $("#sponsorOtherSpecify").hide();
    $("#sponsoredOtherSpecifyValueMessage").hide();
    $("#sponsorOtherSpecifyValueMessage").hide();
    //$(".js-example-disabled-results").select2();
});
var fewSeconds = 5;
$('#thirdFormId,#forthFormId,#firstFormNext,#secondFormNext,#loginButtonId').click(function () {
    // Ajax request
    var btn = $(this);
    btn.prop('disabled', true);
    setTimeout(function () {
        btn.prop('disabled', false);
    }, fewSeconds * 1000);
});

function secondFormOpen() {

}

function firstPopupPrevious() {
    $(".custom-heading").text("Get started for free. No credit card required.");
    $('.firstForm').show();
    $('.thirdForm').hide();
    $('.forthForm').hide();
    // $('.secondForm').hide();
}

function secondPopupPrevious() {
    $(".custom-heading").text("Get started for free. No credit card required.");
    $('.firstForm').hide();
    $('.thirdForm').hide();
    $('.forthForm').hide();
    $('.secondForm').show();
}

function getCity(type) {
    $('.loader').show();
    if (type == 1) {
        var countryID = $("#OfferCountry").val();
    } else if (type == 2) {
        var countryID = $("#ReceiverCountry").val();
    } else if (type == 3) {
        var countryID = $("#country_bid").val();
    } else if (type == 4) {
        var countryID = $("#country_bid_edit").val();
    } else if (type == 5) {
        var countryID = $("#c-filter").val();
    } else if (type == 6) {
        var countryID = $("#vouch_country").val();
    } else if (type == 7) {
        var countryID = $("#filter_c").val();
    }else if (type == 8) {
        var countryID = $("#opportunity_country").val();
    }else if (type == 9) {
        var countryID = $("#lunch_opportunity_country").val();
    }
    $.ajax({
        type: "POST",
        url: base_url + "/get-city",
        headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
        data: {countryID: countryID},
        success: function (res) {
            $('.loader').hide();
            if (res) {
                if (type == 1) {
                    $("#OfferCity").empty();
                    $.each(res, function (key, value) {
                        $("#OfferCity").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                } else if (type == 2) {
                    $("#ReceiveCity").empty();
                    $.each(res, function (key, value) {
                        $("#ReceiveCity").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                } else if (type == 3) {
                    $("#city_bid").empty();
                    $("#city_bid").append('<option value="141852">Across the Country </option>');
                    $.each(res, function (key, value) {
                        $("#city_bid").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                } else if (type == 4) {
                    $("#city_bid_edit").empty();
                    $("#city_bid_edit").append('<option value="141852">Across the Country </option>');
                    $.each(res, function (key, value) {
                        $("#city_bid_edit").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                } else if (type == 5) {
                    $("#city-filter").empty();
                    $("#city-filter").append('<option value="">Select City</option>');
                    $.each(res, function (key, value) {
                        $("#city-filter").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                } else if (type == 6) {
                    $("#vouch_city").empty();
                    $("#vouch_city").append('<option value="141852">Across the Country </option>');
                    $.each(res, function (key, value) {
                        $("#vouch_city").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                } else if (type == 7) {
                    $("#f_c").empty();
                    $("#f_c").append('<option value="141852">Across the Country </option>');
                    $.each(res, function (key, value) {
                        $("#f_c").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }else if (type == 8) {
                    $("#opportunity_city").empty();
                    $("#opportunity_city").append('<option value="141852">Across the Country </option>');
                    $.each(res, function (key, value) {
                        $("#opportunity_city").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }else if (type == 9) {
                    $("#lunch_opportunity_city").empty();
                    $("#lunch_opportunity_city").append('<option value="">Select City</option>');
                    $("#lunch_opportunity_city").append('<option value="141852">Across the Country </option>');
                    $.each(res, function (key, value) {
                        $("#lunch_opportunity_city").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            } else {
                $("#OfferCity").empty();
                $("#ReceiveCity").empty();
            }
        }
    });
}


//form variable
var name = '';
var email = '';
var user_password = '';
var entity = '';
var organisation = '';
var sponsorType = '';
var country = '';
var sponsorFor = '';
var sponsorForSpecify = '';
var sponsorCountry = '';
var sponsorDealSize = '';
var sponsorIndustry = '';

var optionValue = 1;

function setValueOption() {
    flag = $("#offerOption").val();
    if (flag != '') {
        optionValue = flag;
    }

}

function secondPopup() {
    $(".custom-heading").text("The information you will share here doesn't seek accuracy rather it's just the ideation of your generic experience and could be edited at any point of time by you.");
    sponsorType = optionValue = $("#offerOption").val();

    if ($("#name").val() != '' && $("#email").val() != '' && $("#entity").val() != '' && $("#password").val() != '' && $("#organisation").val() != '' && $("#country").val() != '') {
        $("#nameMessage").hide();
        $("#emailMessage").hide();
        $("#passwordMessage").hide();
        $("#organisationMessage").hide();
        $("#countryMessage").hide();
        name = $("#name").val();
        email = $("#email").val();
        user_password = $("#password").val();
        organisation = $("#organisation").val();
        entity = $("#entity").val();
        country = $("#country").val();
        $.ajax({
            type: 'POST',
            url: base_url + '/half-register',
            headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
            data: {email: email, password: user_password, country: country, sponsor_type: sponsorType, entity: entity},
            success: function (data) {
                if (data.status) {

                } else {
                    toastr.info(data.errors);
                    setTimeout(function () {
                        location.href = base_url + '/';
                    }, 1000);
                }
            }
        })
        if (optionValue == 1) {
            $('.firstForm').hide();
            $('.thirdForm').show();
            $('.forthForm').hide();
            $('.secondForm').hide();
        } else {
            $('.firstForm').hide();
            $('.thirdForm').hide();
            $('.forthForm').show();
            $('.secondForm').hide();
        }


    } else {
        toastr.info('All Fileds are mandatory');

    }
}

function validateEmail(emailField) {
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,15})$/;

    if (reg.test(emailField.value) == false) {
        //toastr.info('Please Enter valid email');

        return false;
    }

    return true;

}


function checkFirstPopupValidation() {
    flag = $("#offerOption").val();
    if (flag != '') {
        if ($('#firstCheckBox').is(":checked")) {
            if (flag == 1) {
                $('.firstForm').hide();
                $('.thirdForm').show();
                $('.forthForm').hide();
            } else {
                $('.firstForm').hide();
                $('.thirdForm').hide();
                $('.forthForm').show();
            }
        } else {
            toastr.info('Please accept terms and conditions');
        }
    } else {
        toastr.info('Please Select any');
    }
}

function submitForm() {
    optionValue = $("#offerOption").val();
    if (optionValue == 1) {

        if ($("#OfferEntity").val() != '' && $("#OfferEmail").val() != '' && $("#OfferPhoneNo").val() != '' && $("#sponsoredType").val() != '' && $("#OfferPassword").val() != '' && $("#OfferCountry").val() != '' && $("#OfferCity").val() != '' && $("#OfferQuestion1").val() != '') {
            OfferEntity = $("#OfferEntity").val();
            OfferEmail = $("#OfferEmail").val();
            OfferPhoneNo = $("#OfferPhoneNo").val();
            OfferPassword = $("#OfferPassword").val();
            OfferCountry = $("#OfferCountry").val();
            OfferCity = $("#OfferCity").val();
            OfferQuestion1 = $("#OfferQuestion1").val();
            formData = {
                entity: OfferEntity,
                email: OfferEmail,
                password: OfferPassword,
                country: OfferCountry,
                phone_no: OfferPhoneNo,
                city: OfferCity,
                sq1: OfferQuestion1,
                sponsor_type: optionValue
            };

            formDataSubmit(formData)
        } else {
            toastr.info('All Fileds are mandatory');

        }
    } else {
        if ($("#ReceiveEntity").val() != '' && $("#ReceivceEmail").val() != '' && $("#ReceivePhoneNo").val() != '' && $("#ReceivePassword").val() != '' && $("#ReceiverCountry").val() != '' && $("#ReceiveCity").val() != '' && $("#ReceiveQuestion1").val() != '' && $("#ReceiveIdentity").val() != '') {
            ReceiveEntity = $("#ReceiveEntity").val();
            ReceivceEmail = $("#ReceivceEmail").val();
            ReceivePhoneNo = $("#ReceivePhoneNo").val();
            ReceivePassword = $("#ReceivePassword").val();
            ReceiverCountry = $("#ReceiverCountry").val();
            ReceiveCity = $("#ReceiveCity").val();
            ReceiveQuestion1 = $("#ReceiveQuestion1").val();
            ReceiveIdentity = $("#ReceiveIdentity").val();
            formData = {
                entity: ReceiveEntity,
                email: ReceivceEmail,
                password: ReceivePassword,
                country: ReceiverCountry,
                phone_no: ReceivePhoneNo,
                city: ReceiveCity,
                sq1: ReceiveQuestion1,
                indetity: ReceiveIdentity,
                sponsor_type: optionValue
            };
            formDataSubmit(formData)
        } else {
            toastr.info('All Fileds are mandatory');
        }

    }
}

function formDataSubmit(Data) {
    $('.loader').show();
    $.ajax({
        type: 'POST',
        url: base_url + '/register',
        headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
        data: Data,
        success: function (data) {
            $('.loader').hide();
            if (data.status) {
                $('.firstForm')[0].reset();
                $('.thirdForm')[0].reset();
                $('.forthForm')[0].reset();

                toastr.success(data.message);
                setTimeout(function () {
                    location.href = base_url + '/thankyou';
                }, 1000);
            } else {
                toastr.info(data.errors);
            }
        }
    })
}


function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function (e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) {
            return false;
        }
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function (e) {
                    /*insert the value for the autocomplete text field:*/
                    inp.value = this.getElementsByTagName("input")[0].value;
                    /*close the list of autocompleted values,
                    (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);
            }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
            }
        }
    });

    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }

    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
}

// $( "#email" ).click(function() {
//   					toastr.info('We prefer official email addresses since the validation is easier for any inputs');

// });
$("#email").focus(function () {
    $("#emailNew").show();
});
$("#email").blur(function () {
    $("#emailNew").hide();
});
$("#OfferEmail").focus(function () {
    $("#emailNew1").show();
});
$("#OfferEmail").blur(function () {
    $("#emailNew1").hide();
});
$("#ReceivceEmail").focus(function () {
    $("#emailNew2").show();
});
$("#ReceivceEmail").blur(function () {
    $("#emailNew2").hide();
});

function displayOther(flagSpecify) {
    if (flagSpecify == 1) {
        $("#sponsorOtherSpecify").show();
    } else {
        $("#sponsoredOtherSpecify").show();
    }

    if ($("#sponsoredType").val() == "Event" || $("#sponsorType").val() == "Event") {
        $('#sponsorOtherSpecifyValue').attr("placeholder", "e.g Conference, Music Festival, Tradeshow, Exhibition etc");
        $('#sponsoredOtherSpecifyValue').attr("placeholder", "e.g Conference, Music Festival, Tradeshow, Exhibition etc");
        var sv = ["Conference", "Music Festival", "Tradeshow", "Exhibition"];
    } else if ($("#sponsoredType").val() == "Campaign" || $("#sponsorType").val() == "Campaign") {
        setTimeout(function () {
            console.log($("#sponsoredType").val())
            console.log($("#sponsorType").val())
            $('#sponsorOtherSpecifyValue').attr("placeholder", "e.g. Online, Offline, Social Media, Infleucer etc");
            $('#sponsoredOtherSpecifyValue').attr("placeholder", "e.g. Online, Offline, Social Media, Infleucer etc");

        }, 1000);

        var sv = ["Online", "Offline", "Social Media", "Infleucer"];
    } else if ($("#sponsoredType").val() == "Content" || $("#sponsorType").val() == "Content") {
        $('#sponsorOtherSpecifyValue').attr("placeholder", "e.g. Blog, Video, Inforgraphics, Case Studies, Whitpapers, Articles, Interviews, Memes/ GIFs etc");
        $('#sponsoredOtherSpecifyValue').attr("placeholder", "e.g. Blog, Video, Inforgraphics, Case Studies, Whitpapers, Articles, Interviews, Memes/ GIFs etc");

        var sv = ["Blog", "Video", "Inforgraphics", "Case Studies", "Whitpapers", "Articles", "Interviews", "Memes/ GIFs"];
    } else if ($("#sponsoredType").val() == "Sports Team" || $("#sponsorType").val() == "Sports Team") {
        $('#sponsorOtherSpecifyValue').attr("placeholder", "e.g. Football, Regional, Adventure Sports, Racetrack, International etc");
        $('#sponsoredOtherSpecifyValue').attr("placeholder", "e.g. Football, Regional, Adventure Sports, Racetrack, International etc");

        var sv = ["Football", "Regional", "Adventure Sports", "Racetrack", "International"];
    } else if ($("#sponsoredType").val() == "Other" || $("#sponsorType").val() == "Other") {
        $('#sponsorOtherSpecifyValue').attr("placeholder", "Please Specify");
        $('#sponsoredOtherSpecifyValue').attr("placeholder", "Please Specify");

        var sv = ["Test"];
    } else {
        $('#sponsorOtherSpecifyValue').attr("placeholder", "e.g Conference, Music Festival, Tradeshow, Exhibition etc");
        $('#sponsoredOtherSpecifyValue').attr("placeholder", "e.g Conference, Music Festival, Tradeshow, Exhibition etc");

        var sv = [];
    }
    if (sv.length == 0) {
        console.log(sv);
        $("#sponsorOtherSpecify").hide();
        $("#sponsoredOtherSpecify").hide();
    }
    console.log(sv)
    var countries = ["Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Anguilla", "Antigua & Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia"];
    // autocomplete(document.getElementById("sponsorOtherSpecifyValue"), sv);
    // autocomplete(document.getElementById("sponsoredOtherSpecifyValue"), sv);


}

$(document).ready(function(){
$('.client-logos-new').slick({
	infinite: true,
	slidesToShow: 4,
	slidesToScroll: 1,
	arrows: true,
	dots: false,
	autoplay: false,
	autoplaySpeed: 2000,
	  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: false
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {

        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
	]


});
    });
