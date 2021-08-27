//-----Init
{
    /**
     * Save all default error messages
     */
    $('body .invalid-feedback').each(function() {
        $(this).data('reqmsg', $(this).html());
    });
}

//-----Element events

/**
 * Dynamically update error messages on input
 */
$('input[type=text], input[type=email], select').on('keyup change', function() {
    let val = $(this).val();
    let msg = '';
    let error = false;
    let $msglabel = $(this).parent().find('.invalid-feedback');

    if ($(this).hasClass('onlyeng') && val.match(/[А-Яа-яЁё]+/g)) {
        error = true;
        msg = containsRusLettersError;
    }
    // Show validation messages only after the form was validated
    else if ($(this).closest('.was-validated').length > 0) {
        if (val === '' && $(this).attr('required') === 'required') {
            error = true;
            msg = $msglabel.data('reqmsg');
        } else if ($(this).attr('type') === 'email' && $(this).is(':invalid')) {
            error = true;
            msg = wrongEmailAddress;
        }
    }

    if (error) {
        $(this).addClass('is-invalid');
        $msglabel.html(msg);
    } else $(this).removeClass('is-invalid');
});

/**
 * Disable validation message on radio-btn selection or check change
 */
$('.radiogroup, .checkbox').change(function() {
    $(this).removeClass('is-invalid');
    // Disable validation message for the whole group
    if ($(this).closest('.buttongroup').length) {
        $(this).closest('.buttongroup').removeClass('is-invalid');
    }
});

/**
 * Autoset scientific status on prefix change
 */
$('select[name="Honorific"]').on('change', function() {
    if ($(this).val() == 'Prof.' || $(this).val() == 'Dr.') {
        $('#sciStatusD').attr('checked', 'checked');
        $('#sciStatusD').closest('.radiogroup').removeClass('is-invalid');
    }
});

/**
 * Show input for residency
 */
$('input[name="Residency"]').change(function() {
    if ($(this).val() !== 'R') safeCollapse($('#country'), 'show');
    else safeCollapse($('#country'), 'hide');
});

/**
 * Show input for organization
 */
$('input[name="OrgSelect"]').change(function() {
    if ($(this).val() === 'O') safeCollapse($('#organization'), 'show');
    else safeCollapse($('#organization'), 'hide');
});

/**
 * Show input for speaker
 */
$('input[name="IsPresenter"]').change(function() {
    if ($(this).val() !== 'N') safeCollapse($('#presentation'), 'show');
    else safeCollapse($('#presentation'), 'hide');
});

/**
 * Clear error message on clear search
 */
$('#search').on('keyup', function() {
    $(this).removeClass('is-invalid');
});

/**
 * Search for the article by ID or info
 */
$('#search').autocomplete({
    minLength: 1,
    appendTo: '#list-container',
    source: function(request, resolve) {
        $.get(ADMIN_URL, { action: 'search_papers_json', 'kw': request.term }, function(response) {
            let papers = [];
            let res = JSON.parse(response);

            if (res != null) {
                for (let i = 0; i < res.length; i++) {
                    let paper = res[i];
                    papers.push({
                        value: paper.ID,
                        ecid: paper.ECID,
                        title: paper.Title,
                        authors: paper.Authors,
                        decision: paper.Decision,
                        label: '[' + paper.ECID + '] ' + paper.Authors + ' "' + paper.Title + '"'
                    });
                }
            }
            resolve(papers);
        });
    },
    select: function(event, ui) {
        event.preventDefault();

        if (ui.item.value === '') return;

        let $search = $('#search');
        let $papers = $('#paperGroup').find('.form-row');
        let $paper = null;

        // Clear input field
        $search.val('');

        // Look for an invisible paper (less than 2 papers added)
        $papers.each(function() {
            if ($paper === null && !$(this).is(':visible')) $paper = $(this);
        });


        // The paper has been already selected
        if (ui.item.value === $('[name="ID_Article1"]').val() || ui.item.value === $('[name="ID_Article2"]').val()) {
            $search.addClass('is-invalid');
            $search.next().html(alreadySelectedPaper);
        }
        // Maximum number of papers reached
        else if ($paper === null) {
            $search.addClass('is-invalid');
            $search.next().html(maximumPapers);
        }
        // Insert paper
        else {
            // Insert info
            $paper.find('input[type="hidden"]').val(ui.item.value)
            $paper.find('.atitle').html('[' + ui.item.ecid + '] ' + ui.item.title);
            $paper.find('.authors').html(ui.item.authors);
            $paper.find('.decision').html(ui.item.decision);
            $paper.find('input[type=radio]').prop('checked', false);

            $paper.find('.oral').prop('disabled', ui.item.decision === 'ACCEPT POSTER');

            // Push back in the list
            if (!$($papers[0]).is(':visible') && $($papers[1]).is(':visible'))
                $($papers[1]).after($($papers[0]));

            safeCollapse($paper, 'show');
        }
    },
    response: function(event, ui) {
        if (!ui.content.length) {
            let noResult = { value: '', label: noPaperFound };
            ui.content.push(noResult);
        }
    }
});

/**
 * Remove article from the list
 */
$('.close').click(function() {
    let $paper = $(this).closest('.form-row');
    $paper.find('input[type="hidden"]').val('');
    safeCollapse($paper, 'hide');

    // Hide previous error msgs
    $('#search').removeClass('is-invalid');
});


/**
 * Handle registration form validation
 */
$('#registrationForm').submit(function(e) {
    e.preventDefault();

    let $form = $(this);

    // Hide status bar
    $('#status-bar').addClass('d-none');

    // Disable submit button
    $form.find('button[type=submit]').prop('disabled', true);
    $form.find('.spinner-border').removeClass('d-none');

    // Set invalid status for non-selected radiogroups
    $('input[type=radio]').each(function() {
        if ($(this).is(':invalid')) $(this).closest('.radiogroup').addClass('is-invalid');
    });

    // Set invalid status for non-selected checkboxes
    $('input[type=checkbox]').each(function() {
        if ($(this).is(':invalid')) $(this).closest('.checkbox').addClass('is-invalid');
    });

    // Set invalid status for presenter with 0 papers
    if ($('#isPresenterY').is(':checked') && !$('input[name=ID_Article1]').val().length && !$('input[name=ID_Article2]').val().length) {
        $('#search').addClass('is-invalid');
        $('#search').next().html($('#search').next().data('reqmsg'));
    }

    // Set invalid status for the form if there are invalid components (but visible)
    $form.find('.is-invalid').each(function() {
        if ($(this).is(':visible')) $form.addClass('not-valid');
    });

    // Block submission if there are invalid components
    if ($form.hasClass('not-valid')) {
        $form.find('button[type=submit]').prop('disabled', false);
        $form.find('.spinner-border').addClass('d-none');
        return;
    }

    // Check consistency between the residency and the country selected
    if ($('#residencyR').is(':checked')) {
        $('select[name="Country"]').val('RUS');
    } else if ($('select[name="Country"]').val() === 'RUS') {
        $('#residencyR').prop('checked', true);
    }

    // Check consistency between the organization and the country selected
    if ($('#orgSelectS').is(':checked')) {
        $('input[name="Organization"]').val('Samara University');
        $('input[name="OrganizationCity"]').val('Samara');
    } else if ($('#orgSelectI').is(':checked')) {
        $('input[name="Organization"]').val('Image Processing Systems Institute');
        $('input[name="OrganizationCity"]').val('Samara');
    }

    // Check consistency between the role and the number of papers
    if ($('#isPresenterN').is(':checked')) {
        $('input[name="YouthSchool"]').prop('checked', false);
        $('input[name="ID_Article1"]').val('');
        $('input[name="ID_Article2"]').val('');
    }

    // Collect data
    let fd = new FormData(this);
    fd.append('action', 'form_update_json');

    // Process data
    $.ajax({
        type: 'POST',
        url: ADMIN_URL,
        contentType: false,
        processData: false,
        data: fd,
        success: function(response) {
            let data = JSON.parse(response);

            if (2 === data[0]) {
                $('#status-bar .text').html(data[1]);
                $('#status-bar').removeClass('d-none');
                $("html, body").animate({ scrollTop: 0 }, 300);
            } else {
                $('#regok').removeClass('d-none');
                $('#reg').addClass('d-none');
                // Created
                if (data[3]) {
                    $('#regok .created').removeClass('d-none');
                }
                // Edited 
                else {
                    $('#regok .edited').removeClass('d-none');
                }
                // Confirmation is needed
                if (data[4]) {
                    $('#regok .confirmation').removeClass('d-none');
                }
                $('#editregbtn').attr('href', SITE_URL + '/' + data[2]);
            }
            console.log('finish');
        },
        error: function() {
            $('#status-bar .text').html(noServer);
            $('#status-bar').removeClass('d-none');
            $("html, body").animate({ scrollTop: 0 }, 300);
        },
        complete: function() {
            console.log('renable');
            $form.find('button[type=submit]').prop('disabled', false);
            $form.find('.spinner-border').addClass('d-none');
        }
    });
});

/**
 * Handle restore form validation
 */
$('#restoreForm').submit(function(e) {
    e.preventDefault();

    let $form = $(this);

    // Hide status bar
    $('#status-bar').addClass('d-none');

    // Disable submit button
    $form.find('button[type=submit]').prop('disabled', true);
    $form.find('.spinner-border').removeClass('d-none');

    // Block submission if there are invalid components
    if ($form.hasClass('not-valid')) {
        $form.find('button[type=submit]').prop('disabled', false);
        $form.find('.spinner-border').addClass('d-none');
        return;
    }

    // Collect data
    let fd = new FormData(this);
    fd.append('action', 'form_restore_json');

    // Process data
    $.ajax({
        type: 'POST',
        url: ADMIN_URL,
        contentType: false,
        processData: false,
        data: fd,
        success: function(response) {
            let data = JSON.parse(response);

            if (2 === data[0]) {
                $('#status-bar .text').html(data[1]);
                $('#status-bar').removeClass('d-none');
                $("html, body").animate({ scrollTop: 0 }, 300);
            } else {
                $('#restok').removeClass('d-none');
                $('#rest').addClass('d-none');
            }
        },
        error: function() {
            $('#status-bar .text').html(noServer);
            $('#status-bar').removeClass('d-none');
            $("html, body").animate({ scrollTop: 0 }, 300);
        },
        complete: function() {
            $form.find('button[type=submit]').prop('disabled', false);
            $form.find('.spinner-border').addClass('d-none');
        }
    });
});