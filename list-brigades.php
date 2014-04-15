<?php

    $ctm_api_base = 'http://codeforamerica.org/api';
    $brigades_url = "{$ctm_api_base}/organizations.geojson";
    $geojson = json_decode(file_get_contents($brigades_url), true);
    // Sort the geojson alphabetically
    function alphabet_sort($a, $b)
      {
        return strcmp($a['properties']['city'], $b['properties']['city']);
      }
    usort($geojson['features'], "alphabet_sort");

    if(!function_exists('h'))
    {
        function h($s) 
        {
            return htmlspecialchars($s);
        }
    }

    $base_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    
?>
<ul id="brigades-list" style="display: none">
    <? foreach($geojson['features'] as $feature) {
            if ($feature['properties']['type'] == "Brigade") {
                $id = $feature['id'];
                $p = $feature['properties'];
                $c = $feature['geometry']['coordinates'];
                $on = ($id == $brigade_slug) ? 1 : 0;
                ?>
                <li data-lat="<?= h($c[1]) ?>" data-lon="<?= h($c[0]) ?>" data-on="<?= h($on) ?>" data-id="<?= h($id) ?>">
                    <a href="<?= $base_url.'/index/'.rawurlencode($id) ?>"><?= h($p['name']) ?></a>
                </li>
                <?
            }

        } ?>
</ul>

<ul class="list-no-bullets layout-breve" id="brigades-list-mobile">

  <h4 id="brigade-info">The Code for America Brigade program is an international network of people committed
  to using their voices and hands, in collaboration with local governments, to make their cities better.</h4>

  <div id="join-form">

    <button id="show-form">Want to get connected?</button>

    <form accept-charset="UTF-8" id="new_user" novalidate="novalidate" style="display: none;">
      <input id="no_brigade" type="hidden" name="source" value="no_brigade">
      <ul class="list-form">
        <li class="form-field">
            <label for="user_full_name">Full name</label>
            <input id="user_full_name" name="user[full_name]" type="text" placeholder="Ben Franklin">
        </li>
        <li class="form-field">
            <label for="user_email">Email</label>
            <input id="user_email" name="user[email]" type="text" placeholder="benfranklin@codeforamerica.org">
        </li>
        <li class="form-field">
            <label for="user_work_in_geovernment"><input id="user_work_in_government" name="user[work_in_government]" type="checkbox" value="1">I work in government</label>
        </li>
        <li class="form-field">
            <label for="source"><input id="organizer" value="organizer" type="checkbox">I want to lead a Brigade in my community!</label>
        </li>
        <select id="user_location_id" name="user[location_id]" style="display:none;">
          <option value></option>
        </select>

      </ul>
      <input id="user_human_check" name="user[human_check]" size="50" type="hidden">
      <input name="utf8" type="hidden" value="✓">
    </form>
    <button id="button" style="display: none;">Join</button>
  </div>

  <div id="no_brigade_text" style="display:none;">
    <p><b>Thanks for your interest in a Code for America Brigade in your community.</b></p>
    <p>In the meantime, we’ll keep in touch about opportunities to participate in activities at the national level.</p>
    <p>Remember, if you change your mind, you can always come back to sign up to be an organizer and take a more proactive role.</p>
    <p>Good luck and we'll be in touch.</p>
    <br>
    <p><i>Brigade Support Team</i></p>
    <p><a href='mailto:brigade-info@codeforamerica.org'>brigade-info@codeforamerica.org</a></p>
  </div>

  <div id="organizer_text" style="display:none;">
    <p><b>Thanks for signing up to organize in your community.</b></p>
    <p>Currently, we are hosting a Brigade Organizers hangout outlining your next steps. We hope you will join.</p>
    <p>Tuesday, May 6th, from 5:00 PM to 6:00 PM PDT <a href='https://www.eventbrite.com/e/code-for-america-brigade-leaders-orientation-may-2014-tickets-11305730745'>RSVP</a>
    <p>If you are writing us from abroad and can't make this time please contact our International Programs Manager at <a href="mailto:lynn@codeforamerica.org">lynn@codeforamerica.org</a>.</p>
    <p>In the meantime, we have put together some materials for you to start looking through.</p>
    <p><a href='http://codeforamerica.org/brigade/tools'>http://codeforamerica.org/brigade/tools</a></p>
    <p>Thanks again and see you soon.</p>
    <br>
    <p><i>Brigade Support Team</i></p>
    <p><a href='mailto:brigade-info@codeforamerica.org'>brigade-info@codeforamerica.org</a></p>
  </div>

  <script>
    $("#show-form").click(function(e){
      $("#new_user").show();
      $("#button").show();
      $("#show-form").hide();
    })

    $("#button").click(function(e){
      e.preventDefault();

      // Check that the form is filled out
      if ($("#user_full_name").val() && $("#user_email").val()) {

        // Post form data to old Brigade site
        data = $("#new_user").serialize();
        $.post("http://old-brigade.codeforamerica.org/members", data);

        // If no Brigade selected, show appropriate thanks
        if ($("#no_brigade").is('[name]')){
          $("#no_brigade_text").show();
        }

        // If Organizing, show appropriate thanks
        if ($("#organizer").is('[name]')) {
          $("#organizer_text").show();
        }

        $("#brigade-info").hide()
        $("#join-form").hide()
        $("#button").hide()
      }


    });

    $('#organizer').bind('change', function(){
      if ($('#organizer').is(':checked')) {
        $("#no_brigade").attr("name",null);
        $("#organizer").attr("name","source");
      } else {
        $("#no_brigade").attr("name","source");
        $("#organizer").attr("name",null);
      }
    });

  </script>

  <br />

  <? foreach($geojson['features'] as $feature) {
          if ($feature['properties']['type'] == "Brigade") {
              $p = $feature['properties'];
              ?>
              <li class="billboard">
                  <a href="<?= h($p['website']) ?>"><?= h($p['name']) ?></a>
                  <strong class="billboard-label"><?= h($p['city']) ?></strong>
              </li>
              <?
          }

      } ?>

</ul>
