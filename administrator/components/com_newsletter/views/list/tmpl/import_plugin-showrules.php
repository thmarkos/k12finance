<fieldset>
    <legend><?php echo JText::_($this->plugin->title); ?></legend>
    <form id="plugin-form" action="<?php echo JUri::current(); ?>">
		<?php if (!empty($this->plugin->data['helpText'])) { ?>
			<span class="helptext">
				<?php echo JText::_($this->plugin->data['helpText']); ?>
			</span>
		<?php } ?>

		<?php
		if (!empty($this->plugin->data['options'])) { 
			foreach($this->plugin->data['options'] as $name => $options) { ?>
				<div class="nofloat">
					<?php if (!empty($options['label'])) { ?>
						<label>
							<?php echo JText::_($options['label']); ?>
						</label>
					<?php } ?>
					<?php echo JHtml::_('select.genericlist', $options['list'], "jform[$name]", null, 'value', 'text', !empty($options['selected'])? $options['selected'] : null); ?>
				</div>    
		<?php } } ?>    

		<div class="form-actions">
			<input 
				type="button" 
				class="button plugin-icon" 
				role="formSubmit" 
				value="<?php echo JText::_('JSELECT'); ?>" />
		</div>

		<input type="hidden" name="pluginevent" value="onMigurImportShowRules" />
		<input type="hidden" name="pluginname" value="<?php echo $this->plugin->name; ?>" />

		<input type="hidden" name="option" value="com_newsletter" />
		<input type="hidden" name="tmpl" value="component" />
		<input type="hidden" name="task" value="list.importPluginTrigger" />
		<input type="hidden" name="format" value="html" />
		<input type="hidden" name="list_id" value="<?php echo $this->listId; ?>" />
		<?php JHtml::_('form.token'); ?>
    </form> 
    
    <div class="plugin-description">
        <?php echo JText::_($this->plugin->description); ?>
    </div>
    
</fieldset>