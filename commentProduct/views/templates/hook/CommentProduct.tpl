{if $comments}
    {foreach from=$comments item=element}

        <div class="module-item-wrapper-grid">
            <div class="module-container"
                {$element.comment} | <a href="mailto:{$element.email}">
                {$element.firstname}
            </a>

        </div>
        </div>
    {/foreach}

{/if}

{if $messageResult == 'true'}

    <div class="alert alert-succcess" role="alert">
        <p class="alert-text">Thank you for your review</p>
    </div>
    {elseif $messageResult == 'false'}
    Something went wrong!
{/if}

<form action="" method="post">
    <fieldset class="form-group">
        <label class="form-control-label" for="exampleInput1">Type your message</label>
        <textarea
               required name = "comment" id="comment" cols="30" rows="10"></textarea>
    </fieldset>
        <br>
        <input type="Submit" class="btn btn-primary-outline" value="Submit">

</form>
