<h1>Visit schedule list</h1>
<table width="100%" cellpadding="4" cellspacing="1" class="schedule_table">
    <tr>
        <th>Week Day</th>
        <?php echo($data['object_names']); ?>
    </tr>
    <?php echo($data['table_content']); ?>
</table>

<div class="main_form">
    <h2>Add new item to the schedule</h2>
    <form action="schedule/send_form" method="POST" id="schedule_form" class="">
        <div class="form_item">
            <label for="realtor" class="item_label">Choose realtor:</label>
            <div class="item">
                <select id="realtor" name="realtor_id">
                    <option disabled="disabled" selected="selected">-</option>
                    <?php echo($data['realtor_options']); ?>
                </select>
            </div>
        </div>
        <div class="form_item">
            <label for="calendar" class="item_label">Choose date:</label>
            <div class="item">
                <input name="calendar" id="calendar" type="date" />
            </div>
        </div>
        <div class="form_item">
            <label for="object" class="item_label">Choose object:</label>
            <div class="item">
                <select id="object" name="object">
                    <option disabled="disabled" selected="selected">-</option>
                    <?php echo($data['object_options']); ?>
                </select>
            </div>
        </div>
        <div class="form_item">
            <label for="timeslots" class="item_label">Choose time slot:</label>
            <div class="item">
                <select id="timeslots" name="time">
                    <option disabled="disabled" selected="selected">-</option>
                    <?php echo($data['timeslots']); ?>
                </select>
            </div>
        </div>
        <div class="form_item">
            <label for="customers" class="item_label">Choose Customer:</label>
            <div class="item">
                <select id="customers" name="customers[]">
                    <option disabled="disabled" selected="selected">-</option>
                    <?php echo($data['customer_options']); ?>
                </select>
            </div>
        </div>
        <div id="result"></div>
        <div class="add_one_more_customer" id="add_one_more_customer">Add one more customer</div>

        <button type="submit" class="submit_button">Send</button>
    </form>
</div>
