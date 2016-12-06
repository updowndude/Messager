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
            lblUser.Text = ""+Session["fName"]+" "+Session["lName"];
            lblFullName.Text = ""+Session["fName"]+" "+Session["lName"];
            lblBDay.Text = Session["bDay"].ToString();
            btnAddGroup.Attributes.Add("disabled", "true");
            btnFeedbackSumbit.Attributes.Add("disabled", "false");

            if (IsPostBack == true)
            {
                sqlAllUsers.Select(DataSourceSelectArguments.Empty);
                getGroups.Select(DataSourceSelectArguments.Empty);
            }
        }

        protected void btnLogOut_Click(object sender, EventArgs e)
        {
            Session.Abandon();
            Response.Redirect("../index.aspx", false);
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
                    foreach (DataRowView rowView2 in dvSql2)
                    {
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
            Response.Redirect("post.aspx", false);
        }
    }
}