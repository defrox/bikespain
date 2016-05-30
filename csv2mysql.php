<?php

/**
 * Created by PhpStorm.
 * User: defrox
 * Date: 26/05/16
 * Time: 22:36
 */

namespace defrox;

include('MysqliDb.php');
use \MysqliDb;

/**
 * Class CSV2mySQL
 */
class CSV2mySQL
{
    protected $file = '';
    protected $outfile = '';
    protected $csv = array();
    protected $role = '';
    protected $sql = '';
    protected $entity = 0;
    protected $entity_uid = 4000;
    protected $taxonomy = "profile_type";
    protected $dbhost = "localhost";
    protected $dbuser = "root";
    protected $dbpass = "design";
    protected $dbname = "bikespain";
    protected $dbport = "3306";
    protected $dbexec = FALSE;
    protected $dbconn;
    protected $errors = array();
    protected $inserts = 0;

    /**
     * @param $file
     * @param string $outfile
     * @param string $role
     * @param int $entity
     * @return string
     * @throws \Exception exception
     */
    public function process($file, $outfile = 'output.sql', $role = 'proveedor', $entity = 0)
    {
        $this->file = $file;
        $this->outfile = $outfile;
        $this->role = $role;
        $this->entity = $entity;

        try {
            if (!file_exists($this->file))
                throw new \Exception ("File does not exits! Please select an existent file." . "\n");
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }

        if (!$this->dbconn) $this->setConnection($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname, $this->dbport);
        try {
            if (!$this->dbconn)
                throw new \Exception ("Could not connect to database. Check your configuration." . "\n");
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }

        $this->csv = $this->parseCSV();
        $this->sql = $this->generateSQL();
        $this->createOutputFile($this->sql);

        return "Success: file <a href='" . $this->outfile . "'>" . $this->outfile . "</a> succesfully created.";
    }

    /**
     * @param $dbhost
     * @param $dbuser
     * @param $dbpass
     * @param $dbname
     * @param $dbport
     * @return MysqliDb
     */
    public function setConnection($dbhost, $dbuser, $dbpass, $dbname, $dbport)
    {
        $this->dbhost = $dbhost ? $dbhost : $this->dbhost;
        $this->dbname = $dbname ? $dbname : $this->dbname;
        $this->dbuser = $dbuser ? $dbuser : $this->dbuser;
        $this->dbpass = $dbpass ? $dbpass : $this->dbpass;
        $this->dbport = $dbport ? $dbport : $this->dbport;
        $this->dbconn = new \MysqliDb($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname, $this->dbport);
        try {
            $this->dbconn->mysqli();
            if ($this->dbconn->getLastError() != '') throw new \Exception ($this->dbconn->getLastError());
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
        return $this->dbconn->mysqli();
    }

    /**
     * @return mixed
     */
    public function getDbconn()
    {
        return $this->dbconn;
    }

    /**
     * @return mixed
     */
    public function getDbErrors()
    {
        return $this->dbconn->getLastError();
    }

    /**
     * @param bool $dbexec
     */
    public function setDbexec($dbexec = FALSE)
    {
        $this->dbexec = $dbexec;
    }

    /**
     * @param $taxonomy
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * @param string $sql_string
     * @return string
     * @throws \Exception
     */
    public function dbExec($sql_string)
    {
        try {
            $res = $this->dbconn->rawSQL($sql_string);
            if ($res->error !== '') throw new \Exception ($res->error);
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
        $inserts = $res->affected_rows;
        return "Success: SQL executed successfully, $inserts rows inserted.";
    }

    /**
     * @return array
     */
    private function parseCSV()
    {
        $csv = array_map('str_getcsv', file($this->file));
        array_walk($csv, function (&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);
        return $csv;
    }

    /**
     * @param $txt
     */
    private function createOutputFile($txt)
    {
        $myfile = fopen($this->outfile, "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
        chmod($this->outfile, 0777);
        //chown($this->outfile, 'www-data');
    }

    /**
     * @return string
     */
    private function generateSQL()
    {
        $sql_string = '';
        $tbl_customer_address = $tbl_customer_profile = $tbl_customer_profile_revision = $tbl_razon_social = $tbl_cif_nif = $tbl_type = $tbl_telefono = $tbl_telefono2 = $tbl_email_ = $tbl_notas = $tbl_contact = array();
        $entity = $this->entity;
        $entity_uid = $this->entity_uid;
        $role = $this->role;
        $now = time();
        $pass = '$S$DAn5wWLWqPBHYi4N1jDq5MBUB62ju6mDBIrFXFTYozUefUYlMv';
        $roles = array( 'cliente' => 5, 'proveedor' => 6, 'contacto' => 7 );

        foreach ($this->csv as $row) {

            //Clean up some special characters
            foreach ($row as $key => $value) {
                $row[$key] = preg_replace("/'/", "\\'", $value);
            }

            // Map some vars
            $row_profile = $this->map_taxonomy($row['field_type'], $this->taxonomy);
            $row_country = $this->map_country($row['country']);
            $row_administrative_area = $this->map_administrative_area($row['administrative_area']);

            if (($role == 'cliente' || $role == 'contacto') && (strtolower($row['field_bundle']) == 'cliente' || strtolower($row['field_bundle']) == 'contacto')) $row_rid= strtolower($row['field_bundle']);
            else $row_rid = $role;
            $user_role = $roles[$row_rid];

            $row_bundle = "billing";
            $entity_uid += $entity;

            if ( (!array_key_exists('field_first_name', $row) || $row['field_first_name'] == '') && $row['field_organisation'] != '') $row['field_name_line'] = $row['field_organisation'];
            else $row['field_name_line'] = $row['field_first_name'] . " " . $row['field_last_name'];
            $uemail = preg_split('/; ?| \/ /', $row['field_email']);

            // users
            $row_users = "($entity_uid, ";                                // uid
            $row_users .= "'" . $row['field_name_line'] . "', ";          // name
            $row_users .= "'$pass', ";                                    // pass
            $row_users .= "'" . $uemail[0] . "', ";                       // mail
            $row_users .= "'', ";                                         // theme
            $row_users .= "'', ";                                         // signature
            $row_users .= "'filtered_html', ";                            // signature_format
            $row_users .= "$now, ";                                       // created
            $row_users .= "$now, ";                                       // access
            $row_users .= "$now, ";                                       // login
            $row_users .= "0, ";                                          // status
            $row_users .= "'Europe/Madrid', ";                            // timezone
            $row_users .= "'es', ";                                       // language
            $row_users .= "0, ";                                          // picture
            $row_users .= "'system@bikespain.com', ";                     // init
            $row_users .= "NULL)";                                        // data
            $tbl_users[] = $row_users;

            // users_role
            $row_users_roles = "($entity_uid, ";                                // uid
            $row_users_roles .= "$user_role)";                                  // rid
            $tbl_users_roles[] = $row_users_roles;

            // commerce_customer_address
            $row_customer_address = "('commerce_customer_profile', ";           // entity_type
            $row_customer_address .= "'$row_bundle', ";                         // bundle
            $row_customer_address .= "0, ";                                     // deleted
            $row_customer_address .= "$entity, ";                               // entity_id
            $row_customer_address .= "$entity, ";                               // revision_id
            $row_customer_address .= "'und', ";                                 // language
            $row_customer_address .= "0, ";                                     // delta
            $row_customer_address .= "'" . $row_country . "', ";                // commerce_customer_address_country
            $row_customer_address .= "'" . $row_administrative_area . "', ";    // commerce_customer_address_administrative_area
            $row_customer_address .= "NULL, ";                                  // commerce_customer_address_sub_administrative_area
            $row_customer_address .= "'" . $row['locality'] . "', ";            // commerce_customer_address_locality
            $row_customer_address .= "NULL, ";                                  // commerce_customer_address_dependent_locality
            $row_customer_address .= "'" . $row['postal_code'] . "', ";         // commerce_customer_address_postal_code
            $row_customer_address .= "'" . $row['thoroughfare'] . "', ";        // commerce_customer_address_thoroughfare
            $row_customer_address .= "'', ";                                    // commerce_customer_address_premise
            $row_customer_address .= "NULL, ";                                  // commerce_customer_address_sub_premise
            $row_customer_address .= "'" . $row['field_organisation'] . "', ";  // commerce_customer_address_organisation_name
            $row_customer_address .= "'" . $row['field_name_line'] . "', ";     // commerce_customer_address_name_line
            $row_customer_address .= "'" . (array_key_exists('field_first_name', $row) ? $row['field_first_name'] : $row['field_name_line']) . "', ";    // commerce_customer_address_first_name
            $row_customer_address .= "'" . (array_key_exists('field_last_name', $row) ? $row['field_last_name'] : $row['field_name_line']) . "', ";     // commerce_customer_address_last_name
            $row_customer_address .= "NULL)";                                   // commerce_customer_address_data
            $tbl_customer_address[] = $row_customer_address;

            // commerce_customer_profile
            $row_customer_profile = "($entity, ";                                    // profile_id
            $row_customer_profile .= "$entity, ";                                    // revision_id
            $row_customer_profile .= "'$row_bundle', ";                              // type
            $row_customer_profile .= "$entity_uid, ";                                // uid
            $row_customer_profile .= "1, ";                                          // status
            $row_customer_profile .= "$now, ";                                       // created
            $row_customer_profile .= "$now, ";                                       // changed
            $row_customer_profile .= "NULL)";                                        // data
            $tbl_customer_profile[] = $row_customer_profile;

            // commerce_customer_profile_revision
            $row_customer_profile_revision = "($entity, ";                                    // profile_id
            $row_customer_profile_revision .= "$entity, ";                                    // revision_id
            $row_customer_profile_revision .= "1, ";                                          // revision_uid
            $row_customer_profile_revision .= "1, ";                                          // status
            $row_customer_profile_revision .= "'', ";                                         // log
            $row_customer_profile_revision .= "$now, ";                                       // revision_timestamp
            $row_customer_profile_revision .= "NULL)";                                        // data
            $tbl_customer_profile_revision[] = $row_customer_profile_revision;

            // field_razon_social
            $row_razon_social = "('commerce_customer_profile', ";                // entity_type
            $row_razon_social .= "'$row_bundle', ";                              // bundle
            $row_razon_social .= "0, ";                                          // deleted
            $row_razon_social .= "$entity, ";                                    // entity_id
            $row_razon_social .= "$entity, ";                                    // revision_id
            $row_razon_social .= "'und', ";                                      // language
            $row_razon_social .= "0, ";                                          // delta
            $row_razon_social .= "'" . $row['field_razon_social'] . "', ";       // field_razon_social_value
            $row_razon_social .= "NULL)";                                        // field_razon_social_format
            $tbl_razon_social[] = $row_razon_social;

            // 	field_cif_nif
            $row_cif_nif = "('commerce_customer_profile', ";                     // entity_type
            $row_cif_nif .= "'$row_bundle', ";                                   // bundle
            $row_cif_nif .= "0, ";                                               // deleted
            $row_cif_nif .= "$entity, ";                                         // entity_id
            $row_cif_nif .= "$entity, ";                                         // revision_id
            $row_cif_nif .= "'und', ";                                           // language
            $row_cif_nif .= "0, ";                                               // delta
            $row_cif_nif .= "'" . $row['field_cif_nif'] . "', ";                 // field_cif_nif_value
            $row_cif_nif .= "NULL)";                                             // field_cif_nif_format
            $tbl_cif_nif[] = $row_cif_nif;

            // 	field_type
            $row_type = "('commerce_customer_profile', ";                        // entity_type
            $row_type .= "'$row_bundle', ";                                      // bundle
            $row_type .= "0, ";                                                  // deleted
            $row_type .= "$entity, ";                                            // entity_id
            $row_type .= "$entity, ";                                            // revision_id
            $row_type .= "'und', ";                                              // language
            $row_type .= "0, ";                                                  // delta
            $row_type .= "'" . $row_profile . "')";                              // field_type_tid
            $tbl_type[] = $row_type;

            // 	field_telefono
            $row_telefono = "('commerce_customer_profile', ";                     // entity_type
            $row_telefono .= "'$row_bundle', ";                                   // bundle
            $row_telefono .= "0, ";                                               // deleted
            $row_telefono .= "$entity, ";                                         // entity_id
            $row_telefono .= "$entity, ";                                         // revision_id
            $row_telefono .= "'und', ";                                           // language
            $row_telefono .= "0, ";                                               // delta
            $row_telefono .= "'" . $row['field_telefono'] . "', ";                // field_telefono_value
            $row_telefono .= "NULL)";                                             // field_telefono_format
            $tbl_telefono[] = $row_telefono;

            // 	field_telefono2
            $row_telefono2 = "('commerce_customer_profile', ";                     // entity_type
            $row_telefono2 .= "'$row_bundle', ";                                   // bundle
            $row_telefono2 .= "0, ";                                               // deleted
            $row_telefono2 .= "$entity, ";                                         // entity_id
            $row_telefono2 .= "$entity, ";                                         // revision_id
            $row_telefono2 .= "'und', ";                                           // language
            $row_telefono2 .= "0, ";                                               // delta
            $row_telefono2 .= "'" . $row['field_telefono2'] . "', ";               // field_telefono2_value
            $row_telefono2 .= "NULL)";                                             // field_telefono2_format
            $tbl_telefono2[] = $row_telefono2;

            // 	field_email
            $row['field_email'] = preg_split('/; ?| \/ /', $row['field_email']);
            foreach ($row['field_email'] as $key => $row_email_item) {
                $row_email = "('commerce_customer_profile', ";                         // entity_type
                $row_email .= "'$row_bundle', ";                                       // bundle
                $row_email .= "0, ";                                                   // deleted
                $row_email .= "$entity, ";                                             // entity_id
                $row_email .= "$entity, ";                                             // revision_id
                $row_email .= "'und', ";                                               // language
                $row_email .= "$key, ";                                                // delta
                $row_email .= "'" . $row_email_item . "', ";                           // field_email_value
                $row_email .= "NULL)";                                                 // field_email_format
                $tbl_email[] = $row_email;
            }

            // 	field_notas
            $row_notas = "('commerce_customer_profile', ";                          // entity_type
            $row_notas .= "'$row_bundle', ";                                        // bundle
            $row_notas .= "0, ";                                                    // deleted
            $row_notas .= "$entity, ";                                              // entity_id
            $row_notas .= "$entity, ";                                              // revision_id
            $row_notas .= "'und', ";                                                // language
            $row_notas .= "0, ";                                                    // delta
            $row_notas .= "'" . $row['field_notas'] . "', ";                        // field_notas_value
            $row_notas .= "NULL)";                                                  // field_notas_format
            $tbl_notas[] = $row_notas;

            // 	field_contact
            $row_contact = "('commerce_customer_profile', ";                       // entity_type
            $row_contact .= "'$row_bundle', ";                                     // bundle
            $row_contact .= "0, ";                                                 // deleted
            $row_contact .= "$entity, ";                                           // entity_id
            $row_contact .= "$entity, ";                                           // revision_id
            $row_contact .= "'und', ";                                             // language
            $row_contact .= "0, ";                                                 // delta
            $row_contact .= "'" . $row['field_contact'] . "', ";                   // field_contact_value
            $row_contact .= "NULL)";                                               // field_contact_format
            $tbl_contact[] = $row_contact;

            $entity++;
            if ($entity > 15) break;
        }

        // users TABLES
        $sql_users = "INSERT INTO users (uid, name, pass, mail, theme, signature, signature_format, created, access, login, status, timezone, language, picture, init, data) VALUES ";
        $sql_string .= $sql_users . implode(', ' . "\n", $tbl_users) . ";" . "\n";

        // users_roles TABLES
        $sql_users_roles = "INSERT INTO users_roles (uid, rid) VALUES ";
        $sql_string .= $sql_users_roles . implode(', ' . "\n", $tbl_users_roles) . ";" . "\n";

        // commerce_customer_address TABLES
        $sql_commerce_customer_address_data = "INSERT INTO field_data_commerce_customer_address (entity_type, bundle, deleted, entity_id, revision_id, language, delta, commerce_customer_address_country, commerce_customer_address_administrative_area, commerce_customer_address_sub_administrative_area, commerce_customer_address_locality, commerce_customer_address_dependent_locality, commerce_customer_address_postal_code, commerce_customer_address_thoroughfare, commerce_customer_address_premise, commerce_customer_address_sub_premise, commerce_customer_address_organisation_name, commerce_customer_address_name_line, commerce_customer_address_first_name, commerce_customer_address_last_name, commerce_customer_address_data) VALUES ";
        $sql_string .= $sql_commerce_customer_address_data . implode(', ' . "\n", $tbl_customer_address) . ";" . "\n";
        $sql_commerce_customer_address_revision = "INSERT INTO field_revision_commerce_customer_address (entity_type, bundle, deleted, entity_id, revision_id, language, delta, commerce_customer_address_country, commerce_customer_address_administrative_area, commerce_customer_address_sub_administrative_area, commerce_customer_address_locality, commerce_customer_address_dependent_locality, commerce_customer_address_postal_code, commerce_customer_address_thoroughfare, commerce_customer_address_premise, commerce_customer_address_sub_premise, commerce_customer_address_organisation_name, commerce_customer_address_name_line, commerce_customer_address_first_name, commerce_customer_address_last_name, commerce_customer_address_data) VALUES ";
        $sql_string .= $sql_commerce_customer_address_revision . implode(', ' . "\n", $tbl_customer_address) . ";" . "\n";

        // commerce_customer_profile TABLES
        $sql_commerce_customer_profile_data = "INSERT INTO commerce_customer_profile (profile_id, revision_id, type, uid, status, created, changed, data) VALUES ";
        $sql_string .= $sql_commerce_customer_profile_data . implode(', ' . "\n", $tbl_customer_profile) . ";" . "\n";
        $sql_commerce_customer_profile_revision = "INSERT INTO commerce_customer_profile_revision (profile_id, revision_id, revision_uid, status, log, revision_timestamp, data) VALUES ";
        $sql_string .= $sql_commerce_customer_profile_revision . implode(', ' . "\n", $tbl_customer_profile_revision) . ";" . "\n";

        // field_razon_social TABLES
        $sql_field_razon_social_data = "INSERT INTO field_data_field_razon_social (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_razon_social_value, field_razon_social_format) VALUES ";
        $sql_string .= $sql_field_razon_social_data . implode(', ' . "\n", $tbl_razon_social) . ";" . "\n";
        $sql_field_razon_social_revision = "INSERT INTO field_revision_field_razon_social (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_razon_social_value, field_razon_social_format) VALUES ";
        $sql_string .= $sql_field_razon_social_revision . implode(', ' . "\n", $tbl_razon_social) . ";" . "\n";

        // field_cif_nif TABLES
        $sql_field_cif_nif_data = "INSERT INTO field_data_field_cif_nif (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_cif_nif_value, field_cif_nif_format) VALUES ";
        $sql_string .= $sql_field_cif_nif_data . implode(', ' . "\n", $tbl_cif_nif) . ";" . "\n";
        $sql_field_cif_nif_revision = "INSERT INTO field_revision_field_cif_nif (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_cif_nif_value, field_cif_nif_format) VALUES ";
        $sql_string .= $sql_field_cif_nif_revision . implode(', ' . "\n", $tbl_cif_nif) . ";" . "\n";

        // field_type TABLES
        $sql_field_type_data = "INSERT INTO field_data_field_type (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_type_tid) VALUES ";
        $sql_string .= $sql_field_type_data . implode(', ' . "\n", $tbl_type) . ";" . "\n";
        $sql_field_type_revision = "INSERT INTO field_revision_field_type (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_type_tid) VALUES ";
        $sql_string .= $sql_field_type_revision . implode(', ' . "\n", $tbl_type) . ";" . "\n";

        // field_telefono TABLES
        $sql_field_telefono_data = "INSERT INTO field_data_field_telefono (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_telefono_value, field_telefono_format) VALUES ";
        $sql_string .= $sql_field_telefono_data . implode(', ' . "\n", $tbl_telefono) . ";" . "\n";
        $sql_field_telefono_revison = "INSERT INTO field_revision_field_telefono (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_telefono_value, field_telefono_format) VALUES ";
        $sql_string .= $sql_field_telefono_revison . implode(', ' . "\n", $tbl_telefono) . ";" . "\n";

        // field_telefono2 TABLES
        $sql_field_telefono2_data = "INSERT INTO field_data_field_telefono2 (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_telefono2_value, field_telefono2_format) VALUES ";
        $sql_string .= $sql_field_telefono2_data . implode(', ' . "\n", $tbl_telefono2) . ";" . "\n";
        $sql_field_telefono2_revision = "INSERT INTO field_revision_field_telefono2 (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_telefono2_value, field_telefono2_format) VALUES ";
        $sql_string .= $sql_field_telefono2_revision . implode(', ' . "\n", $tbl_telefono2) . ";" . "\n";

        // field_email TABLES
        $sql_field_email_data = "INSERT INTO field_data_field_email (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_email_value, field_email_format) VALUES ";
        $sql_string .= $sql_field_email_data . implode(', ' . "\n", $tbl_email) . ";" . "\n";
        $sql_field_email_revision = "INSERT INTO field_revision_field_email (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_email_value, field_email_format) VALUES ";
        $sql_string .= $sql_field_email_revision . implode(', ' . "\n", $tbl_email) . ";" . "\n";

        // field_notas TABLES
        $sql_field_notas_data = "INSERT INTO field_data_field_notas (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_notas_value, field_notas_format) VALUES ";
        $sql_string .= $sql_field_notas_data . implode(', ' . "\n", $tbl_notas) . ";" . "\n";
        $sql_field_notas_revision = "INSERT INTO field_revision_field_notas (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_notas_value, field_notas_format) VALUES ";
        $sql_string .= $sql_field_notas_revision . implode(', ' . "\n", $tbl_notas) . ";" . "\n";

        // field_contact TABLES
        $sql_field_contact_data = "INSERT INTO field_data_field_contact (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_contact_value, field_contact_format) VALUES ";
        $sql_string .= $sql_field_contact_data . implode(', ' . "\n", $tbl_contact) . ";" . "\n";
        $sql_field_contact_revision = "INSERT INTO field_revision_field_contact (entity_type, bundle, deleted, entity_id, revision_id, language, delta, field_contact_value, field_contact_format) VALUES ";
        $sql_string .= $sql_field_contact_revision . implode(', ' . "\n", $tbl_contact) . ";" . "\n";

        return $sql_string;
    }

    /**
     * @param $term
     * @param $taxonomy
     * @return int
     * @throws \Exception
     */
    private function map_taxonomy($term, $taxonomy = "profile_type")
    {
        $term = trim($term);
        $taxonomy = trim($taxonomy);
        $this->dbconn->where("machine_name", $taxonomy);
        try {
            $taxonomy_id = $this->dbconn->getValue("taxonomy_vocabulary", "vid");
            if ($taxonomy_id === NULL)
                throw new \Exception ("The vocabulary does not exist. Please check the configuration.");
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
        $this->dbconn->where("name", $term);
        if ($this->dbconn->has("taxonomy_term_data")) {
            $this->dbconn->where("name", $term);
            $term_id = $this->dbconn->getValue("taxonomy_term_data", "tid");
        } else {
            $data = Array(
                'vid' => $taxonomy_id,
                'name' => $term,
                'description' => $term,
                'format' => "full_html",
                'weight' => 0,
                'language' => "und",
                'i18n_tsid' => 0,
            );
            $term_id = $this->dbconn->insert("taxonomy_term_data", $data);
            $data_hierarchy = Array(
                'tid' => $term_id,
                'parent' => 0,
            );
            $this->dbconn->insert("taxonomy_term_hierarchy", $data_hierarchy);
        };
        return $term_id;
    }

    /**
     * @param $term
     * @return string
     */
    private function map_country($term)
    {
        $country_array = array(
            'ALEM' => 'DE',
            'ARG' => 'AR',
            'AUS' => 'AT',
            'AUST' => 'AU',
            'BELG' => 'BE',
            'BRA' => 'BR',
            'CAN' => 'CA',
            'CHEQ' => 'CZ',
            'CHIL' => 'CL',
            'CHIN' => 'CN',
            'COLO' => 'CO',
            'COST' => 'CR',
            'DENM' => 'DK',
            'ESPA' => 'ES',
            'FINL' => 'FI',
            'FRAN' => 'FR',
            'GBR' => 'GB',
            'GUAT' => 'GT',
            'HOL' => 'NL',
            'IRE' => 'IE',
            'ISRA' => 'IL',
            'ITAL' => 'IT',
            'JAPO' => 'JP',
            'KORE' => 'KR',
            'MARR' => 'MA',
            'MEXI' => 'MX',
            'NOR' => 'NO',
            'NUEV' => 'NZ',
            'PER' => 'PE',
            'POLO' => 'PL',
            'PR' => 'PR',
            'PORT' => 'PT',
            'PRT' => 'PT',
            'RUSI' => 'RU',
            'SIN' => 'SG',
            'SUDA' => 'SD',
            'SUEC' => 'SE',
            'SUIZ' => 'CH',
            'URU' => 'UY',
            'USA' => 'US',
        );
        $country_code = array_key_exists($term, $country_array) ? $country_array[$term] : 'ES';
        return $country_code;
    }

    /**
     * @param $term
     * @return string
     */
    private function map_administrative_area($term)
    {
        $provincias_array = array(
            "01" => "VI",
            "02" => "AB",
            "03" => "A",
            "04" => "AL",
            "05" => "AV",
            "06" => "BA",
            "07" => "PM",
            "08" => "B",
            "09" => "BU",
            "10" => "CC",
            "11" => "CA",
            "12" => "CS",
            "13" => "CR",
            "14" => "CO",
            "15" => "C",
            "16" => "CU",
            "17" => "GI",
            "18" => "GR",
            "19" => "GU",
            "20" => "SS",
            "21" => "H",
            "22" => "HU",
            "23" => "J",
            "24" => "LE",
            "25" => "L",
            "26" => "LO",
            "27" => "LU",
            "28" => "M",
            "29" => "MA",
            "30" => "MU",
            "31" => "NA",
            "32" => "OR",
            "33" => "O",
            "34" => "P",
            "35" => "GC",
            "36" => "PO",
            "37" => "SA",
            "38" => "TF",
            "39" => "S",
            "40" => "SG",
            "41" => "SE",
            "42" => "SO",
            "43" => "T",
            "44" => "TE",
            "45" => "TO",
            "46" => "V",
            "47" => "VA",
            "48" => "BI",
            "49" => "ZA",
            "50" => "Z",
            "51" => "CE",
            "52" => "ML",
        );
        $administrative_area = array_key_exists($term, $provincias_array) ? $provincias_array[$term] : '';
        return $administrative_area;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }
}