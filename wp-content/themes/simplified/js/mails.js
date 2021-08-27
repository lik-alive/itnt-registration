var datatable = $("#datatable").DataTable({
  autoWidth: false,
  dom:
    "<'row'<'col-sm-12'<tr>>>" +
    "<'row'<'align-self-center col-md-5 mb-2 mb-md-0'l><'col-md-7'p>>",
  pageLength: 25,
  lengthMenu: [
    [10, 25, 50, -1],
    [10, 25, 50, "всё"]
  ],
  stateSave: true,
  stateSaveParams: function (settings, data) {
    data.search.search = "";
  },
  pagingType: "numbers",
  serverSide: true,
  processing: true,
  language: {
    emptyTable: "<div style='text-align: center'>Записей нет</div>",
    zeroRecords: "<div style='text-align: center'>Совпадений не найдено</div>",
    loadingRecords: "<div style='text-align: center'>Загружается...</div>",
    processing: `<div>Обрабатывается...</div>`,
    lengthMenu: "Показать _MENU_"
  },
  ajax: {
    url: ADMIN_URL + "?action=email_list_s_json",
    dataType: "json",
    contentType: "application/json; charset=utf-8"
  },

  order: [[2, "desc"]],

  columns: [
    { name: "id", data: "ID", visible: false },
    {
      name: "status",
      data: "Status",
      orderSequence: ["desc"],
      render: function (data, type, JsonResultRow, meta) {
        if (type === "filter") return data;
        else {
          let meta = JSON.parse(JsonResultRow.MetaInfo);
          let tostr = "";
          meta.TO.forEach(e => {
            if ("" !== tostr) tostr += ", ";
            tostr += escapeHtml(`${e.Name} <${e.Email}>`);
          });

          if (meta.Extra.Link !== undefined) {
            tostr = `<a href='/${meta.Extra.Link}'>${tostr}</a>`;
          }

          let files = "";
          if (meta.Attachments !== null) {
            meta.Attachments.forEach(e => {
              if (files.length) files += ", ";
              files += `<a href='${e.url}' target='_blank'>${escapeHtml(
                e.name
              )}</a>`;
            });
          }
          if ("" === files) files = "-нет-";

          let retrybtn = "";
          let forcebtn = "";
          let status_short = "Ожидает отправки";
          let status_style = "info";
          if ("X" === JsonResultRow.Status) {
            status_short = "Ошибка отправления";
            status_style = "danger";
            retrybtn = `<button class='btn btn-info retry'>Повторить</button>`;
          } else if ("F" === JsonResultRow.Status) {
            status_short = "Отправлено";
            status_style = "success";
          } else {
            forcebtn = `<button class='btn btn-success force'>Отправить немедленно</button>`;
          }

          data = `<div class='position-relative'>
								<div class='d-flex flex-wrap'>
									<div class='py-1 text-center bg-${status_style}' style='width:200px'>${status_short}</div>
									<div class='flex-fill py-1 text-right pr-1'><i class='zmdi zmdi-calendar'></i> ${JsonResultRow.SendDateTime}</div>
								</div>
								
								<div class='mt-1 mx-2'>
									<div class='font-weight-bold'>${meta.Subject}</div>
									<div class='authors'>${tostr}</div>
									<div class='mb-1'>
										<i class='fas fa-file'></i> Файлы: ${files}
									</div>
								</div>

								<div style='position: absolute;bottom: 2px;right: 5px;'>
									${forcebtn}
									${retrybtn}
									<button class='btn btn-danger remove' >Удалить</button>
								</div>
							</div>
							<button class='btn btn-info btn-block collapser collapsed textview' data-target='#col${JsonResultRow.ID}' data-toggle='collapse'>
								Посмотреть текст
								<i class="fas fa-chevron-down"></i>
								<i class="fas fa-chevron-up"></i>
							</button>
							<div id='col${JsonResultRow.ID}' class='collapse'>
								<iframe class='w-100 mt-2' style='min-height:400px'>${JsonResultRow.Text}</iframe>
							</div>`;

          return data;
        }
      }
    },
    { name: "pcount", data: "SendDateTime", orderSequence: ["desc", "asc"] }
  ],

  createdRow: function (row, data, dataIndex) {
    $("td:eq(0)", row).attr("colspan", 2);
    $("td:eq(1)", row).css("display", "none");
  },

  drawCallback: function (settings) {
    var api = this.api();
    var rows = api.rows();
    var frows = api.rows({ filter: "applied" }); //After search apply
    if (rows[0].length == 0) return;

    var jsdata = api.ajax.json().data;

    //Show row-count
    $(this)
      .closest(".info-panel")
      .find(".row-count")
      .html("(" + api.page.info().recordsTotal + ")");

    api
      .column(0)
      .nodes()
      .each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
  }
});

datatable.on("click", ".force", function () {
  let tr = $(this).closest("tr");
  let data = datatable.row(tr).data();

  showConfirmDialog("Отправить письмо немедленно?", function () {
    $.post(
      ADMIN_URL,
      { action: "email_force_json", ID: data.ID },
      function (response) {
        AddStatusMsg(JSON.parse(response));
        datatable.ajax.reload();
      }
    );
  });
});

datatable.on("click", ".retry", function () {
  let tr = $(this).closest("tr");
  let data = datatable.row(tr).data();

  $.post(
    ADMIN_URL,
    { action: "email_retry_json", ID: data.ID },
    function (response) {
      AddStatusMsg(JSON.parse(response));
      datatable.ajax.reload();
    }
  );
});

datatable.on("click", ".remove", function () {
  let tr = $(this).closest("tr");
  let data = datatable.row(tr).data();

  showConfirmDialog("Удалить письмо?", function () {
    $.post(
      ADMIN_URL,
      { action: "email_remove_json", ID: data.ID },
      function (response) {
        AddStatusMsg(JSON.parse(response));
        datatable.ajax.reload();
      }
    );
  });
});

datatable.on("click", ".textview", function () {
  let $self = $(this);
  let $field = $(this).next();
  let tr = $(this).closest("tr");
  let data = datatable.row(tr).data();

  if (!$field.is(":visible")) {
    $.get(
      ADMIN_URL,
      { action: "email_get_json", ID: data.ID },
      function (response) {
        let data = JSON.parse(response);
        if (data[0] === 2) AddStatusMsg(data);
        else $field.find("iframe").contents().find("body").html(data[1].Text);
      }
    );
  }
});
