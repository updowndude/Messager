using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace Messenger
{
    public partial class groups : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            btnAddGroup.Attributes.Add("disabled", "false");
            btnFeedbackSumbit.Attributes.Add("disabled", "false");
            btnAdimLogin.Attributes.Add("disabled", "false");

            sqlClicker.SelectCommand = "SELECT * FROM [person] WHERE(([fName] = '" + Session["fName"] + "') AND ([lName] = '" + Session["lName"] + "') AND ([birthday] = #" + Session["bDay"] + " 00:00:00#)) ";
            DataView dvSql = (DataView)(sqlClicker.Select(DataSourceSelectArguments.Empty));
            foreach (DataRowView rowView in dvSql)
            {
                if (rowView["picture"].ToString() != "")
                {
                    imgPersonPicture.ImageUrl = "../uploads/"+rowView["picture"].ToString();
                }
            }

            if (!IsPostBack)
            {
                lblUser.Text = ""+Session["fName"]+" "+Session["lName"];
                lblFullName.Text = ""+Session["fName"]+" "+Session["lName"];
                lblBDay.Text = Session["bDay"].ToString();
            }
            else
            {
                dlGroups.DataBind();
                dlGroups2.DataBind();
                dlistGroups.DataBind();
                dlistGroups2.DataBind();
                dlUser.DataBind();
            }

            dlFeedbackGroup.DataBind();
        }

        protected void btnLogOut_Click(object sender, EventArgs e)
        {
            Session.Abandon();
            Response.Redirect("Home", false);
        }

        protected void btnAddGroup_Click(object sender, EventArgs e)
        {
            String groupID = "";
            String personID = "";

            if(txtGroupName.Text.Trim() != "")
            {
                try
                {
                    sqlClicker.InsertCommand = "INSERT INTO [groups] ([name], [data_added]) VALUES ('" + txtGroupName.Text + "', '" + DateTime.Today + "')";
                    sqlClicker.Insert();
                    sqlClicker.SelectCommand = "SELECT TOP 1  * FROM groups ORDER BY groups_id DESC";
                    DataView dvSql = (DataView)(sqlClicker.Select(DataSourceSelectArguments.Empty));
                    foreach (DataRowView rowView in dvSql)
                    {
                        groupID = rowView["groups_id"].ToString();
                    }

                    sqlClicker.SelectCommand = "SELECT * FROM [person] WHERE(([fName] = '" + Session["fName"] + "') AND ([lName] = '" + Session["lName"] + "') AND ([birthday] = #" + Session["bDay"] + " 00:00:00#)) ";
                    DataView dvSql2 = (DataView)(sqlClicker.Select(DataSourceSelectArguments.Empty));
                    foreach (DataRowView rowView2 in dvSql2) {

                        personID = rowView2["person_id"].ToString();
                    }
                    sqlClicker.InsertCommand = "INSERT INTO [people_group] ([groups_id], [person_id], [posted]) VALUES ('" + groupID + "', '" + personID + "', #" + DateTime.Today + "#) ";
                    sqlClicker.Insert();

                    txtGroupName.Text = "";
                }
                catch (Exception)
                {
                    txtGroupName.Focus();
                    txtGroupName.Text = "";
                }
                
            }
            else
            {
                txtGroupName.Focus();
                txtGroupName.Text = "";
            }
        }

        protected void btnUser_Click(object sender, EventArgs e)
        {
            try
            {
                sqlClicker.InsertCommand = "INSERT INTO [people_group] ([groups_id], [person_id], [posted]) values (" + dlGroups.SelectedValue + ", " + dlUser.SelectedValue + "+, #" + DateTime.Today + "#)";
                sqlClicker.Insert();
            }
            catch (Exception)
            {
                btnUser.Focus();
            }
            
        }

        protected void btnFeedbackSumbit_Click(object sender, EventArgs e)
        {
            String personID = "";

            if(txtMessage.Text.Trim() != "") {

                try
                {
                    sqlClicker.SelectCommand = "SELECT * FROM [person] WHERE(([fName] = '" + Session["fName"] + "') AND ([lName] = '" + Session["lName"] + "') AND ([birthday] = #" + Session["bDay"] + " 00:00:00#)) ";
                    DataView dvSql2 = (DataView)(sqlClicker.Select(DataSourceSelectArguments.Empty));
                    foreach (DataRowView rowView2 in dvSql2)
                    {
                        personID = rowView2["person_id"].ToString();
                    }

                    if (int.Parse(dlFeedbackGroup.SelectedValue) == -1)
                    {
                        sqlClicker.InsertCommand = "INSERT INTO [feedback] ([message], [rating], [person_id], [placed]) VALUES('" + txtMessage.Text + "', '" + dlRating.SelectedValue + "', '" + personID + "', #" + DateTime.Today + "#)";
                        sqlClicker.Insert();
                    }
                    else
                    {
                        sqlClicker.InsertCommand = "INSERT INTO [feedback] ([message], [rating], [person_id], [groups_id], [placed]) VALUES('" + txtMessage.Text + "', " + dlRating.SelectedValue + ", " + personID + ", " + dlGroups.SelectedValue + ", #" + DateTime.Today + "#)";
                        sqlClicker.Insert();

                    }
                }
                catch (Exception)
                {
                    txtMessage.Focus();
                    txtMessage.Text = "";
                }

                txtMessage.Text = "";
                dlFeedbackGroup.SelectedIndex = 0;
            }
            else
            {
                txtMessage.Focus();
                txtMessage.Text = "";
            }
        }

        protected void btnName_Click(object sender, EventArgs e)
        {
            Button btn = (Button)sender;
            Session["name"] = btn.Text;
            Response.Redirect("Post", false);
        }

        protected void btnAdimLogin_Click(object sender, EventArgs e)
        {
            if (txtAdimKey.Text.Trim() != "") {
                try
                {
                    sqlClicker.SelectCommand = "SELECT adim_key FROM [adims] WHERE [adim_key] = '"+txtAdimKey.Text+"'";
                    DataView dvSql2 = (DataView)(sqlClicker.Select(DataSourceSelectArguments.Empty));
                    foreach (DataRowView rowView2 in dvSql2)
                    {
                        Session["adim"] = rowView2["adim_key"];
                    }

                    txtAdimKey.Text = "";
                }
                catch (Exception)
                {
                    txtAdimKey.Focus();
                    txtAdimKey.Text = "";
                }
            }
            else
            {
                txtAdimKey.Focus();
                txtAdimKey.Text = "";
            }
        }

        protected void btnGroupDelete_Click(object sender, EventArgs e)
        {
            Button btn = (Button)sender;
            sqlClicker.DeleteCommand = "DELETE FROM [people_group] WHERE [groups_id] = " + btn.Text + "";
            sqlClicker.Delete();

            sqlClicker.DeleteCommand = "DELETE FROM groups WHERE [groups_id] = " + btn.Text + "";
            sqlClicker.Delete();
        }

        protected void btnPicture_Click(object sender, EventArgs e)
        {
            if (filePicture.HasFile)
            {
                try
                {
                    if ((filePicture.PostedFile.ContentType == "image/jpeg") || (filePicture.PostedFile.ContentType == "image/png"))
                    {
                        if (filePicture.PostedFile.ContentLength <= 10000000)
                        {
                            if (filePicture.FileName.IndexOf('.') <= 4)
                            {
                                Random ran = new Random();
                                String strFileName = filePicture.FileName;
                                strFileName = strFileName.Insert(strFileName.IndexOf('.'),ran.Next(0, 9999).ToString());

                                filePicture.SaveAs(Server.MapPath("~/uploads/"+strFileName));
                                sqlClicker.InsertCommand = "UPDATE person set [picture] = '" + strFileName + "' WHERE (([fName] = '" + Session["fName"] + "') AND ([lName] = '" + Session["lName"] + "') AND ([birthday] = #" + Session["bDay"] + " 00:00:00#)) ";
                                sqlClicker.Insert();
                            }
                            else
                            {
                                lblError.Text = "Bad file name";
                            }
                        }
                        else
                        {
                            lblError.Text = "File is to big";
                        }
                    }
                    else
                    {
                        lblError.Text = "Wrong file type";
                    }
                }
                catch (Exception)
                {
                    lblError.Text = "Sorry bad file";
                }
            }
            else
            {
                lblError.Text = "Sorry bad file";
            }
        }

        protected void timLogin_Tick(object sender, EventArgs e)
        {
            Label lblTimer = (Label)upDatTimer.Controls[0].Controls[1];
            DateTime sessionTime = Convert.ToDateTime(Session["loginTime"]);
            TimeSpan dtLoginTime = DateTime.Now.Subtract(sessionTime);
            lblTimer.Text = "Minutes Login: "+dtLoginTime.TotalMinutes.ToString("N2");
        }
    }
}