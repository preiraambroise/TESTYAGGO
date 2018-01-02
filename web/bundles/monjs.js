$(function()
{
    var count = 0;

    // fonction noselection renvoi true si aucune selection 
    var noselection = function () {
        var bool = true;
        $('input').each(function () {
            if ($(this).is(':checked'))
            {
                bool = false;
                return false;
            }
        });
        return bool;
    };

    // fonction de deselection totale
    $('.unselectall').click(function () {
        if (noselection())
        {
            alert("Pas de selection");
        } else
        {
            $('input').each(function () {
                if ($(this).is(':checked'))
                {
                    $(this).prop('checked', false);
                }
            });
        }
    });

    //fonction de selection des personnes au dessus
    $('.select-up').click(function () {
        if (noselection())
        {
            alert("Pas de selection");
        } else
        {
            $('input').each(function () {
                if ($(this).is(':checked'))
                {
                    return false;
                }
                $(this).prop('checked', true);
            });
        }
    });

    //fonction de comptage de selection

    $('.count').click(function () {
        $('input').each(function () {
            if ($(this).is(':checked'))
            {
                count++;
            }
        });
        $('.count-print').html("Nombre de personnes selectionné: " + count);
        count = 0;
    });
    // fonction annulant la soumission de formulaire vide
    $('.btn-default').click(function(event){
        if (noselection())
        {
            alert("Pas de selection");
            event.preventDefault();
        }
    });
});