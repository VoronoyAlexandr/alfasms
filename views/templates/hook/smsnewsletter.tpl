<div id="smsnewsletter_block_left" class="block">
    <h4>SMS рассылка</h4>
    <div class="block_content">
        <form action="{$link->getPageLink('index', null, null, null, false, null, true)|escape:'html':'UTF-8'}" method="post">
            <div class="form-group form-ok" >
                {literal}
                <input class="inputNew form-control greysmsnewsletter-input" id="smsnewsletter-input" type="text" name="SmsNewsLetterPhone" size="18" placeholder="Пример: 0501234567" onkeypress='return event.charCode >= 48 && event.charCode <= 57' pattern=".{10,}"   required title="10 цифр"/>
                {/literal}
                <button type="submit" name="submitSmsNewsletter" class="btn btn-default button button-small">
                    <span>ОК</span>
                </button>
                <input type="hidden" name="action" value="0" />
            </div>
        </form>
    </div>

</div>

{strip}
    {if isset($msg) && $msg}
        {addJsDef msg_newsl=$msg|@addcslashes:'\''}
    {/if}
    {if isset($nw_error)}
        {addJsDef nw_error=$nw_error}
    {/if}

    {if isset($msg) && $msg}
        {addJsDefL name=alert_smsnewsletter}{$msg}{/addJsDefL}
    {/if}
{/strip}
