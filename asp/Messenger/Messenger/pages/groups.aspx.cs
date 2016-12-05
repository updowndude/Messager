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
            lblUser.Text = "" + Session["fName"] +" "+ Session["lName"];
            btnAddGroup.Attributes.Add("disabled", "ture");
        }

        protected void btnLogOut_Click(object sender, EventArgs e)
        {
            Session.Abandon();
            Response.Redirect("../index.aspx", false);
        }

        protected void btnAddGroup_Click(object sender, EventArgs e)
        {
            if(btnAddGroup.Text.Trim() != "")
            {
                sqlAllGroups.InsertCommand = $"INSERT INTO [groups] ([name], [data_added]) VALUES (\"{txtGroupName.Text}\", \"{DateTime.Today}\")";
                sqlAllGroups.Insert();
                sqlAllGroups.SelectCommand = "SELECT TOP 1  * FROM groups ORDER BY groups_id DESC";
                DataView dvSql = (DataView)(sqlAllGroups.Select(DataSourceSelectArguments.Empty));
                txtGroupName.Text = dvSql.ToString();
            }
        }
    }
}