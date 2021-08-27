<div id='confirmModal' class='modal fade'>
	<div class='modal-dialog modal-dialog-top modal-sm'>
		<div class='modal-content'>
			<div class='modal-header'>
				<span class='modal-title w-100 text-center'>Подтверждение</span>
			</div>
			<div class='modal-body' style='font-size:1.1rem'>
				
			</div>
			<div class='modal-footer'>	
				<button class="btn btn-secondary cancel" type='button'>
					<i class="fas fa-times"></i> Нет
				</button>
				<button class="btn btn-primary confirm" type='button'>
					<i class="fas fa-check"></i> <span class='label'>Да</span>
				</button>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
	
		var confirm_actionYes;
		var confirm_actionNo;
		
		// Show confirmation dialog
		function showConfirmDialog(text, actionYes, actionNo = null, title = 'Подтверждение', btns = 'yes-no', large = false) {
			// Set content
			$('#confirmModal .modal-title').html(title);
			$('#confirmModal .modal-body').html(text);

			// Set view
			$('#confirmModal .cancel').toggle('yes-no' === btns);
			$('#confirmModal .confirm .label').html('yes-no' === btns ? 'Да' : 'Ок');
			$('#confirmModal .modal-dialog').toggleClass('modal-sm', !large);
			
			// Set actions
			confirm_actionYes = actionYes;
			confirm_actionNo = actionNo;
			
			$('#confirmModal').modal({backdrop: 'static', keyboard: false});
		}
		
		$('#confirmModal .confirm').click(function() {
			if (null !== confirm_actionYes) confirm_actionYes();
			$('#confirmModal').modal('toggle');
		});

		$('#confirmModal .cancel').click(function() {
			if (null !== confirm_actionNo) confirm_actionNo();
			$('#confirmModal').modal('toggle');
		});
	</script>
</div>	