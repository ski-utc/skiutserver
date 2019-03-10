$(document).ready(function() {

    $('#ajouterMembre').click(function()
                              {
        var c = $("#membres input:last").clone();

        var name = $(c).attr('name');

        prec = parseInt(name.substring(6, name.length));

        if ($(c).val() != '') {
            actu = prec + 1;
            name = 'membre'+actu+'_';

            $(c).attr('name', name);
            $(c).attr('id', name);
            $(c).val('');

            c.autocomplete({
                serviceUrl: 'liste_autocomplete.php',
                dataType: 'json'
            });

            $("#membres").append("<label for=\"Membre_" + actu + "\">Membre " + (actu));
            $("#membres").append(c);
            $("#membres").append("<span class='erreur erreurMembre" + actu + "'></span>");
            $("#membres").append("<br />");
        } else {
            alert("Merci de remplir tous les logins précédents avant d'en ajouter d'autres");
        }
    });

    $('#enleverMembre').click(function()
                              {
        var c = $("#membres input:last");
        name = $(c).attr('name');
        if (parseInt(name.substring(6, name.length)) > 1)
        {
            c.remove();
            var l = $("#membres label:last");
            l.remove();
            var s = $("#membres br:last");
            s.remove();
            var e = $("#membres span:last");
            e.remove();
        }
    });


    $('#envoiForm').click(function()
                          {

        var valid = true;
        var vide = false;

        responsable_ = $("#responsable_").val();
        membre1_ = $("#membre1_").val();
        membre2_ = $("#membre2_").val();
        membre3_ = $("#membre3_").val();
        membre4_ = $("#membre4_").val();
        membre5_ = $("#membre5_").val();
        membre6_ = $("#membre6_").val();
        membre7_ = $("#membre7_").val();
        membre8_ = $("#membre8_").val();
        membre9_ = $("#membre9_").val();
        membre10_ = $("#membre10_").val();
        membre11_ = $("#membre11_").val();

        resp = $("input[name=responsable_]").val();

        for (i = 1 ; i <= 11 ; i++)
        {
            if ($("#membre"+i+"_").val() == '')
            {
                vide = true;
                valid = false;
            }
            $("#membre"+i+"_").next(".erreurMembre"+i).hide();
        }

        if (valid)
        {
            var i = 1;
            var sortie = false;
            while (i <= 11 && !sortie)
            {
                if ($("#membre"+i+"_").val() != null)
                {
                    var j = i+1;
                    while (j <= 11 && !sortie)
                    {
                        if ($("#membre"+i+"_").val() == $("#membre"+j+"_").val())
                        {
                            $("#membre"+i+"_").next(".erreurMembre"+i).fadeIn().text("Logins identiques");
                            $("#membre"+j+"_").next(".erreurMembre"+j).fadeIn().text("Logins identiques");
                            sortie = true;
                            valid = false;
                        }
                        j++;
                    }
                }
                if ($("#membre"+i+"_").val() == resp)
                {
                    $("#membre"+i+"_").next(".erreurMembre"+i).fadeIn().text("Ne pas saisir le login du responsable_");
                    sortie = true;
                    valid = false;
                }
                i++;
            }
        }

        if (valid)
        {
            if (membre1_ != null && membre1_ != '')
            {
                $.ajax({
                    type: "POST",
                    url: "verif_form.php",
                    data: "login="+membre1_,
                    success: function(retour){
                        if (retour == 1)
                        {
                            $("#membre1_").next(".erreurmembre1").fadeIn().text("Login inexistant");
                            valid = false;
							console.log("test");
                        }
                        else if (retour == 2)
                        {
                            $("#membre1_").next(".erreurmembre1").fadeIn().text("Login déjà saisi dans un autre groupe");
                            valid = false;
                        }
                    },
                    async: false
                });
            }

            if (membre2_ != null && membre2_ != '')
            {
                $.ajax({
                    type: "POST",
                    url: "verif_form.php",
                    data: "login="+membre2_,
                    success: function(retour){
                        if (retour == 1)
                        {
                            $("#membre2_").next(".erreurmembre2").fadeIn().text("Login inexistant");
                            valid = false;
                        }
                        else if (retour == 2)
                        {
                            $("#membre2_").next(".erreurmembre2").fadeIn().text("Login déjà saisi dans un autre groupe");
                            valid = false;
                        }
                    },
                    async: false
                });
            }

            if (membre3_ != null && membre3_ != '')
            {
                $.ajax({
                    type: "POST",
                    url: "verif_form.php",
                    data: "login="+membre3_,
                    success: function(retour){
                        if (retour == 1)
                        {
                            $("#membre3_").next(".erreurmembre3").fadeIn().text("Login inexistant");
                            valid = false;
                        }
                        else if (retour == 2)
                        {
                            $("#membre3_").next(".erreurmembre3").fadeIn().text("Login déjà saisi dans un autre groupe");
                            valid = false;
                        }
                    },
                    async: false
                });
            }

            if (membre4_ != null && membre4_ != '')
            {
                $.ajax({
                    type: "POST",
                    url: "verif_form.php",
                    data: "login="+membre4_,
                    success: function(retour){
                        if (retour == 1)
                        {
                            $("#membre4_").next(".erreurmembre4").fadeIn().text("Login inexistant");
                            valid = false;
                        }
                        else if (retour == 2)
                        {
                            $("#membre4_").next(".erreurmembre4").fadeIn().text("Login déjà saisi dans un autre groupe");
                            valid = false;
                        }
                    },
                    async: false
                });
            }

            if (membre5_ != null)
            {
                $.ajax({
                    type: "POST",
                    url: "verif_form.php",
                    data: "login="+membre5_,
                    success: function(retour){
                        if (retour == 1)
                        {
                            $("#membre5_").next(".erreurmembre5").fadeIn().text("Login inexistant");
                            valid = false;
                        }
                        else if (retour == 2)
                        {
                            $("#membre5_").next(".erreurmembre5").fadeIn().text("Login déjà saisi dans un autre groupe");
                            valid = false;
                        }
                    },
                    async: false
                });
            }

            if (membre6_ != null)
            {
                $.ajax({
                    type: "POST",
                    url: "verif_form.php",
                    data: "login="+membre6_,
                    success: function(retour){
                        if (retour == 1)
                        {
                            $("#membre6_").next(".erreurmembre6").fadeIn().text("Login inexistant");
                            valid = false;
                        }
                        else if (retour == 2)
                        {
                            $("#membre6_").next(".erreurmembre6").fadeIn().text("Login déjà saisi dans un autre groupe");
                            valid = false;
                        }
                    },
                    async: false
                });
            }


            if (membre7_ != null)
            {
                $.ajax({
                    type: "POST",
                    url: "verif_form.php",
                    data: "login="+membre7_,
                    success: function(retour){
                        if (retour == 1)
                        {
                            $("#membre7_").next(".erreurmembre7").fadeIn().text("Login inexistant");
                            valid = false;
                        }
                        else if (retour == 2)
                        {
                            $("#membre7_").next(".erreurmembre7").fadeIn().text("Login déjà saisi dans un autre groupe");
                            valid = false;
                        }
                    },
                    async: false
                });
            }

            if (membre8_ != null)
            {
                $.ajax({
                    type: "POST",
                    url: "verif_form.php",
                    data: "login="+membre8_,
                    success: function(retour){
                        if (retour == 1)
                        {
                            $("#membre8_").next(".erreurmembre8").fadeIn().text("Login inexistant");
                            valid = false;
                        }
                        else if (retour == 2)
                        {
                            $("#membre8_").next(".erreurmembre8").fadeIn().text("Login déjà saisi dans un autre groupe");
                            valid = false;
                        }
                    },
                    async: false
                });
            }

            if (membre9_ != null)
            {
                $.ajax({
                    type: "POST",
                    url: "verif_form.php",
                    data: "login="+membre9_,
                    success: function(retour){
                        if (retour == 1)
                        {
                            $("#membre9_").next(".erreurmembre9").fadeIn().text("Login inexistant");
                            valid = false;
                        }
                        else if (retour == 2)
                        {
                            $("#membre9_").next(".erreurmembre9").fadeIn().text("Login déjà saisi dans un autre groupe");
                            valid = false;
                        }
                    },
                    async: false
                });
            }

            if (membre10_ != null)
            {
                $.ajax({
                    type: "POST",
                    url: "verif_form.php",
                    data: "login="+membre10_,
                    success: function(retour){
                        if (retour == 1)
                        {
                            $("#membre10_").next(".erreurmembre10").fadeIn().text("Login inexistant");
                            valid = false;
                        }
                        else if (retour == 2)
                        {
                            $("#membre10_").next(".erreurmembre10").fadeIn().text("Login déjà saisi dans un autre groupe");
                            valid = false;
                        }
                    },
                    async: false
                });
            }

            if (membre11_ != null)
            {
                $.ajax({
                    type: "POST",
                    url: "verif_form.php",
                    data: "login="+membre11_,
                    success: function(retour){
                        if (retour == 1)
                        {
                            $("#membre11_").next(".erreurmembre11").fadeIn().text("Login inexistant");
                            valid = false;
                        }
                        else if (retour == 2)
                        {
                            $("#membre11_").next(".erreurmembre11").fadeIn().text("Login déjà saisi dans un autre groupe");
                            valid = false;
                        }
                    },
                    async: false
                });
            }
        }

        if (vide)
        {
            valid = true;
        }

        return valid;
    });


    $('#membre1_').autocomplete({
        serviceUrl: 'liste_autocomplete.php',
        dataType: 'json'
    });

    $('#membre2_').autocomplete({
        serviceUrl: 'liste_autocomplete.php',
        dataType: 'json'
    });

    $('#membre3_').autocomplete({
        serviceUrl: 'liste_autocomplete.php',
        dataType: 'json'
    });

    $('#membre4_').autocomplete({
        serviceUrl: 'liste_autocomplete.php',
        dataType: 'json'
    });

    $('#membre5_').autocomplete({
        serviceUrl: 'liste_autocomplete.php',
        dataType: 'json'
    });

    $('#membre6_').autocomplete({
        serviceUrl: 'liste_autocomplete.php',
        dataType: 'json'
    });

    $('#membre7_').autocomplete({
        serviceUrl: 'liste_autocomplete.php',
        dataType: 'json'
    });

    $('#membre8_').autocomplete({
        serviceUrl: 'liste_autocomplete.php',
        dataType: 'json'
    });

    $('#membre9_').autocomplete({
        serviceUrl: 'liste_autocomplete.php',
        dataType: 'json'
    });

    $('#membre10_').autocomplete({
        serviceUrl: 'liste_autocomplete.php',
        dataType: 'json'
    });

    $('#membre11_').autocomplete({
        serviceUrl: 'liste_autocomplete.php',
        dataType: 'json'
    });

    $('#voisins_').autocomplete({
        serviceUrl: 'liste_autocomplete.php',
        dataType: 'json'
    });

});



jQuery(function($) {'use strict',

    //#main-slider
    $(function(){
    $('#main-slider.carousel').carousel({
        interval: 8000
    });
});


                    // accordian
                    $('.accordion-toggle').on('click', function(){
                        $(this).closest('.panel-group').children().each(function(){
                            $(this).find('>.panel-heading').removeClass('active');
                        });

                        $(this).closest('.panel-heading').toggleClass('active');
                    });

                    //Initiat WOW JS
                    new WOW().init();

                    // portfolio filter
                    $(window).load(function(){'use strict';
                                              var $portfolio_selectors = $('.portfolio-filter >li>a');
                                              var $portfolio = $('.portfolio-items');
                                              $portfolio.isotope({
                                                  itemSelector : '.portfolio-item',
                                                  layoutMode : 'fitRows'
                                              });

                                              $portfolio_selectors.on('click', function(){
                                                  $portfolio_selectors.removeClass('active');
                                                  $(this).addClass('active');
                                                  var selector = $(this).attr('data-filter');
                                                  $portfolio.isotope({ filter: selector });
                                                  return false;
                                              });
                                             });

                    // Contact form
                    var form = $('#main-contact-form');
                    form.submit(function(event){
                        event.preventDefault();
                        var form_status = $('<div class="form_status"></div>');
                        $.ajax({
                            url: $(this).attr('action'),

                            beforeSend: function(){
                                form.prepend( form_status.html('<p><i class="fa fa-spinner fa-spin"></i> Email is sending...</p>').fadeIn() );
                            }
                        }).done(function(data){
                            form_status.html('<p class="text-success">' + data.message + '</p>').delay(3000).fadeOut();
                        });
                    });


                    //goto top
                    $('.gototop').click(function(event) {
                        event.preventDefault();
                        $('html, body').animate({
                            scrollTop: $("body").offset().top
                        }, 500);
                    });

                    //Pretty Photo
                    $("a[rel^='prettyPhoto']").prettyPhoto({
                        social_tools: false
                    });
                   });
