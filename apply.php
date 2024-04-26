<?php include_once 'header.inc'; ?>
<body>
    <?php include_once 'menu.inc'; ?>

    <div class="apply-web">
        <form method="post" action="processEOI.php" class="form-animated">
            <div class="apply-form">
                <h3 class="form-title">Contact enquiry</h3>
                <p>Looking for a job in AI and Data Analysis? Join Logistic Food for a well-paid position now!</p>
                <p>The interview will take place during company office hours, from 8am to 4pm Monday to Friday.</p>
                <fieldset>
                    <div class="mini-title">
                        <div><h4>Applicant details</h4></div>
                    </div>
                    <div class="main-user-info">
                        <div class="user-input-box">
                            <label for="name">Given name</label> 
                                <input type="text" name="name" id="name" maxlength="20" size="20"
                                pattern="[A-Za-z]+"
                                placeholder="Ex: Will"
                                required="required">
                        </div>
                        <div class="user-input-box">
                            <label for="family">Family name</label>
                                <input type="text" name="family" id="family" maxlength="20" size="20"
                                pattern="[A-Za-z]+"
                                placeholder="Ex: Smith"
                                required="required">
                        </div><br>
                        <div class="user-input-box">
                            <label for="number">Contact number</label>
                                <input type="text" name="number" id="number" maxlength="12" size="12"
                                pattern="[0-9\s]{8,12}"
                                placeholder="8888 8888"
                                required="required">
                        </div><br>
                        <div class="user-input-box">
                            <label for="email">Email</label>
                                <input type="email" name="email" id="email"
                                pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                                placeholder="name@example.com"
                                required="required">
                        </div><br>
                        <div class="user-input-box">
                            <label for="date">Date of Birth</label>  
                            <input type="text" name="date" id="date"
                            placeholder="dd/mm/yyyy"
                            pattern="(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/((19|20)\d\d)">           <!-- year: 19xx- 20xx -->
                        </div><br>
                    
                        <div class="user-input-box">
                            <label for="street">Street address</label>
                            <input type="text" id="street" name="Street" maxlength="40" 
                            placeholder="Street address" 
                            required>
                        </div><br>
                       <div class="user-input-box">
                            <label for="postcode">Postcode</label>
                            <input type="text" id="postcode" name="postcode" length="4" 
                            pattern="[0-9]{4}"
                            placeholder="Ex: 1234" 
                            required>
                        </div><br>
                        <div class="user-input-box">
                            <label for="town">Suburb/Town</label>
                            <input type="text" id="town" name="Town/Suburb" maxlength="40" placeholder="Abbey" required>
                        </div><br>
                        <div class="user-input-box">
                            <label class="label">State</label>
                            <select name="state" id="state" required>
                                <option class="option" value="">Please select your State</option>
                                <option class="option" value="VIC">VIC</option>
                                <option class="option" value="NSW">NSW</option>
                                <option class="option" value="QLD">QLD</option>
                                <option class="option" value="NT">NT</option>
                                <option class="option" value="WA">WA</option>
                                <option class="option" value="SA">SA</option>
                                <option class="option" value="TAS">TAS</option>
                                <option class="option" value="ACT">ACT</option>
                            </select>
                        </div><br>
                    </div>
                        <div class="user-gender">
                            <fieldset>
                                <h5 class="mini-title">Gender</h5><br>
                                <div class="gender-category">
                                    <label for="female">Female</label>
                                    <input type="radio" id="female" name="gender" value="female">
                                    <label for="male">Male</label>
                                    <input type="radio" id="male" name="gender" value="male">
                                </div><br>
                            </fieldset><br>
                        </div>
                    
                </fieldset><br>
            
            
            <div class="job-apply">
                <fieldset>
                    <div class="mini-title">
                        <h4 class="mini-title">Job you are interested in working</h4>
                    </div><br>
                    <div class="user-input-box">
                        <label for="job" class="user-input-box">Job reference number</label>
                        <input type="text" name="job" id="job" 
                               pattern=[A-Za-z0-9]{5}
                               placeholder="Ex: AI101"
                               required="required">
                    </div><br>
                    <div class="skills">
                        <label>Desired skills</label><br>
                        <input type="checkbox" name="skill[]" id="python" value="python" checked="checked">
                        <label for="python">Python</label>
                        
                        <input type="checkbox" name="skill[]" id="nlp" value="nlp">
                        <label for="nlp">NLP</label>
                        
                        <input type="checkbox" name="skill[]" id="pytorch" value="pytorch">
                        <label for="pytorch">PyTorch</label>
                        
                        <input type="checkbox" name="skill[]" id="sql" value="sql">
                        <label for="sql">SQL</label>
                        
                        <input type="checkbox" name="skill[]" id="datavisu" value="datavisu">
                        <label for="datavisu">Data Visualization</label>
                    </div><br>
                    <label for="other" class="user-input-box">Other skills</label><br>
                    <textarea id="other" name="other" rows="4" cols="30"
                    placeholder="Write your other skills..."></textarea><br>
                </fieldset>
            </div>
            <div class="submit-form">
                <input type="submit" value="Apply" name="Finish-apply">
            </div>
            </div>
        </form>
    </div>
    
    <?php include_once 'footer.inc'; ?>
</body>

</html>