<table border=0 cellpadding="1" cellspacing=1 width="100%">
<!--   <input type="hidden" name="us_agy_id" value="{if $us_agy_id neq ''}{$us_agy_id}{else}{$entity_id}{/if}"> -->
    <input type="hidden" name="MAX_FILE_SIZE" value="3145728" /> 
    {if !$show_status_field}
    	<input type="hidden" name="status" value="{$status}" /> 
    {/if}
	<tr>
		<td class="ftitle">Credential Type</td>
		<td>
			{html_options options=$type_list selected=$tab_086_credential_type name='tab_086_credential_type'} *
		</td>
	</tr>
	<tr>
		<td class="ftitle" width="200">Credential Id </td>
		<td><input type="text" name="credential_id" value="{$credential_id}" maxlenght="20"> *
		</td>
	</tr>
	<tr>
		<td class="ftitle" width="200">Expiration Date </td>
		<td><input type="text" name="expiration_date" value="{$expiration_date|date_format}" id="expiration_date" onKeyUp="DateFormat(this,this.value,event,false)" onBlur="DateFormat(this,this.value,event,true)">
		<img src="{$image_url}icon_calendar.png" onclick="if(self.gfPop)gfPop.fStartPop(document.getElementById('expiration_date'), document.getElementById('fecha_help'));return false;" >
		</td>
	</tr>
	<tr>
		<td class="ftitle" width="200">Alert Days </td>
		<td><input type="text" name="alert_days" value="{$alert_days}" size="5" /> </td>
	</tr>
	{if $attachment neq ""}
	<tr>
		<td class="ftitle">View Credential</td>
		<td>
			<a href="{base_url}datastore/credential/{$attachment}" target="_blank" title="View Credential">View Credential</a>
		</td>
	</tr>	
	{/if}
	<tr>
		<td class="ftitle">Upload Credential Copy</td>
		<td>
			<input type="file" name="attachment" />
		</td>
	</tr>	
	<tr>
		<td class="ftitle">Notes</td>
		<td>
			<textarea name="notes" style="width:50%;" wrap="auto" rows="6">{$notes}</textarea>
		</td>
	</tr>
	<tr>
		<td class="ftitle">Validate expiration Date?</td>
		<td>
			<input type="radio" name="verify_expiration" id="verify_expiration_yes" value="yes" {if $verify_expiration neq 'no'}checked="checked"{/if}/> <label for="verify_expiration_yes">Yes</label>
			<input type="radio" name="verify_expiration" id="verify_expiration_no" value="no" {if $verify_expiration eq 'no'}checked="checked"{/if} /> <label for="verify_expiration_no">No</label>
		</td>
	</tr>
	{if $show_status_field}
	<tr>
		<td class="ftitle">Status</td>
		<td>
			<input type="radio" name="status" id="status_Active" value="Active" {if $status eq 'Active'}checked="checked"{/if}/> <label for="status_Active">Active</label>
			<input type="radio" name="status" id="status_Inactive" value="Inactive" {if $status eq 'Inactive'}checked="checked"{/if} /> <label for="status_Inactive">Inactive</label>
		</td>
	</tr>
	{/if}
</table>